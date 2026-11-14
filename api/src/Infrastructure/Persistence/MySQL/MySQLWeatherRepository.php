<?php

declare(strict_types=1);

namespace ADS\TakeHome\Infrastructure\Persistence\MySQL;

use ADS\TakeHome\Application\Weather\DTOs\CityDailyWeatherDTO;
use ADS\TakeHome\Application\Weather\DTOs\CityDTO;
use ADS\TakeHome\Application\Weather\DTOs\RegionDailyWeatherDTO;
use ADS\TakeHome\Application\Weather\Repositories\WeatherRepositoryInterface;
use ADS\TakeHome\Domain\Weather\CalendarDate;
use ADS\TakeHome\Domain\Weather\CityId;
use ADS\TakeHome\Domain\Weather\DateRange;
use ADS\TakeHome\Framework\Database\MySQL;

final class MySQLWeatherRepository implements WeatherRepositoryInterface
{
    private const DB_DATE_FORMAT = 'Ymd';

    public function __construct(
        private readonly MySQL $mysql,
    ) {
    }

    /**
     * @return list<CityDailyWeatherDTO>
     */
    public function findCityWeather(
        CityId $cityId,
        DateRange $dateRange,
    ): array {
        $sql = '
            SELECT
                cities.id AS cityId,
                cities.name AS cityName,
                min_weather.date AS date,
                min_weather.min_temp AS minTemp,
                max_weather.max_temp AS maxTemp,
                precipitation.precipitation AS precipitation
            FROM cities
            INNER JOIN minimum_temperatures AS min_weather ON min_weather.city_id = cities.id
            INNER JOIN maximum_temperatures AS max_weather ON max_weather.city_id = min_weather.city_id AND max_weather.date = min_weather.date
            INNER JOIN precipitation ON precipitation.city_id = min_weather.city_id AND precipitation.date = min_weather.date
            WHERE 
                cities.id = :cityId AND 
                min_weather.date BETWEEN :fromDate AND :toDate
            ORDER BY min_weather.date ASC
        ';

        $rows = $this->mysql->queryAssoc($sql, [
            ':cityId' => $cityId->value,
            ':fromDate' => (int)$dateRange->from->format(self::DB_DATE_FORMAT),
            ':toDate' => (int)$dateRange->to->format(self::DB_DATE_FORMAT),
        ]);

        return array_map(
            static fn (array $row): CityDailyWeatherDTO => new CityDailyWeatherDTO(
                cityId: CityId::fromInt((int)$row['cityId']),
                cityName: (string)$row['cityName'],
                date: self::toCalendarDate($row['date']),
                minTemp: (float)$row['minTemp'],
                maxTemp: (float)$row['maxTemp'],
                precipitation: (float)$row['precipitation'],
            ),
            $rows,
        );
    }

    /**
     * @return list<RegionDailyWeatherDTO>
     */
    public function findRegionWeather(
        string $region,
        DateRange $dateRange,
    ): array {
        $sql = '
            SELECT
                cities.region AS region,
                min_weather.date AS date,
                AVG(min_weather.min_temp) AS minTemp,
                AVG(max_weather.max_temp) AS maxTemp,
                AVG(precipitation.precipitation) AS precipitation
            FROM cities
            INNER JOIN minimum_temperatures AS min_weather ON min_weather.city_id = cities.id
            INNER JOIN maximum_temperatures AS max_weather ON max_weather.city_id = min_weather.city_id AND max_weather.date = min_weather.date
            INNER JOIN precipitation ON precipitation.city_id = min_weather.city_id AND precipitation.date = min_weather.date
            WHERE
                cities.region = :region AND 
                min_weather.date BETWEEN :fromDate AND :toDate
            GROUP BY cities.region, min_weather.date
            ORDER BY min_weather.date ASC
        ';

        $rows = $this->mysql->queryAssoc($sql, [
            ':region' => $region,
            ':fromDate' => (int)$dateRange->from->format(self::DB_DATE_FORMAT),
            ':toDate' => (int)$dateRange->to->format(self::DB_DATE_FORMAT),
        ]);

        return array_map(
            static fn (array $row): RegionDailyWeatherDTO => new RegionDailyWeatherDTO(
                region: (string)$row['region'],
                date: self::toCalendarDate($row['date']),
                minTemp: (float)$row['minTemp'],
                maxTemp: (float)$row['maxTemp'],
                precipitation: (float)$row['precipitation'],
            ),
            $rows,
        );
    }

    public function cityExists(CityId $cityId): bool
    {
        $sql = '
            SELECT EXISTS (
                SELECT 1
                FROM cities
                WHERE id = :cityId
            ) AS isExisting
        ';

        $result = $this->mysql->queryAssoc($sql, [
            ':cityId' => $cityId->value,
        ]);

        return (bool)($result[0]['isExisting'] ?? false);
    }

    public function regionExists(string $region): bool
    {
        $sql = '
            SELECT EXISTS (
                SELECT 1
                FROM cities
                WHERE region = :region
            ) AS isExisting
        ';
        $result = $this->mysql->queryAssoc($sql, [
            ':region' => $region,
        ]);

        return (bool)($result[0]['isExisting'] ?? false);
    }

    /**
     * @return list<CityDTO>
     */
    public function findAllCities(): array
    {
        $rows = $this->mysql->queryAssoc("SELECT id, name, region FROM cities");

        return array_map(
            static fn (array $row): CityDTO => new CityDTO(
                id: CityId::fromInt((int)$row['id']),
                name: (string)$row['name'],
                region: (string)$row['region'],
            ),
            $rows,
        );
    }

    private static function toCalendarDate(int|float|string|bool|null $value): CalendarDate
    {
        return CalendarDate::fromFormat(self::DB_DATE_FORMAT, (string)$value);
    }
}
