<?php

declare(strict_types=1);

namespace ADS\TakeHome\Application\Weather\DTOs;

use ADS\TakeHome\Domain\Weather\DateRange;

final readonly class RegionWeatherQueryDTO
{
    public function __construct(
        public string $region,
        public DateRange $dateRange,
    ) {
    }
}
