<?php

declare(strict_types=1);

namespace ADS\TakeHome\Application\Weather\DTOs;

use ADS\TakeHome\Domain\Weather\CalendarDate;
use JsonSerializable;

final readonly class RegionDailyWeatherDTO implements JsonSerializable
{
    public function __construct(
        public string $region,
        public CalendarDate $date,
        public float $minTemp,
        public float $maxTemp,
        public float $precipitation,
    ) {
    }

    /**
     * @return array{region: string, date: string, minTemp: float, maxTemp: float, precipitation: float}
     */
    public function jsonSerialize(): array
    {
        return [
            'region' => $this->region,
            'date' => $this->date->toIsoString(),
            'minTemp' => $this->minTemp,
            'maxTemp' => $this->maxTemp,
            'precipitation' => $this->precipitation,
        ];
    }
}
