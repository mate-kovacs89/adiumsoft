<?php

declare(strict_types=1);

namespace ADS\TakeHome\Application\Weather\Factories;

use ADS\TakeHome\Application\Weather\DTOs\CityWeatherQueryDTO;
use ADS\TakeHome\Domain\Weather\CalendarDate;
use ADS\TakeHome\Domain\Weather\CityId;
use ADS\TakeHome\Domain\Weather\DateRange;
use InvalidArgumentException;

final class CityWeatherQueryFactory
{
    /**
     * @param array<string, string|list<string>> $data
     *
     * @throws InvalidArgumentException
     */
    public function create(array $data): CityWeatherQueryDTO
    {
        if (!isset($data['cityId']) || !is_string($data['cityId'])) {
            throw new InvalidArgumentException('Missing or invalid parameter: cityId');
        }

        if (!isset($data['from']) || !is_string($data['from'])) {
            throw new InvalidArgumentException('Missing or invalid parameter: from');
        }

        if (!isset($data['to']) || !is_string($data['to'])) {
            throw new InvalidArgumentException('Missing or invalid parameter: to');
        }

        return new CityWeatherQueryDTO(
            cityId: CityId::fromString($data['cityId']),
            dateRange: new DateRange(
                from: CalendarDate::fromString($data['from']),
                to: CalendarDate::fromString($data['to']),
            ),
        );
    }
}
