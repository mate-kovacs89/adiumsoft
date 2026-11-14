<?php

declare(strict_types=1);

namespace ADS\TakeHome\Application\Weather\Repositories;

use ADS\TakeHome\Application\Weather\DTOs\CityDailyWeatherDTO;
use ADS\TakeHome\Application\Weather\DTOs\CityDTO;
use ADS\TakeHome\Application\Weather\DTOs\RegionDailyWeatherDTO;
use ADS\TakeHome\Domain\Weather\CityId;
use ADS\TakeHome\Domain\Weather\DateRange;

interface WeatherRepositoryInterface
{
    /**
     * @return list<CityDailyWeatherDTO>
     */
    public function findCityWeather(
        CityId $cityId,
        DateRange $dateRange,
    ): array;

    public function cityExists(CityId $cityId): bool;

    /**
     * @return list<RegionDailyWeatherDTO>
     */
    public function findRegionWeather(
        string $region,
        DateRange $dateRange,
    ): array;

    public function regionExists(string $region): bool;

    /**
     * @return list<CityDTO>
     */
    public function findAllCities(): array;
}
