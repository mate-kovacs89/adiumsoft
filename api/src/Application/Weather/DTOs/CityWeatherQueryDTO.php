<?php

declare(strict_types=1);

namespace ADS\TakeHome\Application\Weather\DTOs;

use ADS\TakeHome\Domain\Weather\CityId;
use ADS\TakeHome\Domain\Weather\DateRange;

final readonly class CityWeatherQueryDTO
{
    public function __construct(
        public CityId $cityId,
        public DateRange $dateRange,
    ) {
    }
}
