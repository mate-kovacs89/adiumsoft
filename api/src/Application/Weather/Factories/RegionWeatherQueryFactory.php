<?php

declare(strict_types=1);

namespace ADS\TakeHome\Application\Weather\Factories;

use ADS\TakeHome\Application\Weather\DTOs\RegionWeatherQueryDTO;
use ADS\TakeHome\Domain\Weather\CalendarDate;
use ADS\TakeHome\Domain\Weather\DateRange;
use InvalidArgumentException;

final class RegionWeatherQueryFactory
{
    /**
     * @param array<string, string|list<string>> $data
     *
     * @throws InvalidArgumentException
     */
    public function create(array $data): RegionWeatherQueryDTO
    {
        if (!isset($data['region']) || !is_string($data['region'])) {
            throw new InvalidArgumentException('Missing or invalid parameter: region');
        }

        if (!isset($data['from']) || !is_string($data['from'])) {
            throw new InvalidArgumentException('Missing or invalid parameter: from');
        }

        if (!isset($data['to']) || !is_string($data['to'])) {
            throw new InvalidArgumentException('Missing or invalid parameter: to');
        }

        return new RegionWeatherQueryDTO(
            region: $data['region'],
            dateRange: new DateRange(
                from: CalendarDate::fromString($data['from']),
                to: CalendarDate::fromString($data['to']),
            ),
        );
    }
}
