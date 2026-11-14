<?php

declare(strict_types=1);

namespace ADS\TakeHome\Application\Weather\DTOs;

use ADS\TakeHome\Domain\Weather\CalendarDate;
use ADS\TakeHome\Domain\Weather\CityId;
use JsonSerializable;

final readonly class CityDailyWeatherDTO implements JsonSerializable
{
    public function __construct(
        public CityId $cityId,
        public string $cityName,
        public CalendarDate $date,
        public float $minTemp,
        public float $maxTemp,
        public float $precipitation,
    ) {
    }

    /**
     * @return array{cityId: int, cityName: string, date: string, minTemp: float, maxTemp: float, precipitation: float}
     */
    public function jsonSerialize(): array
    {
        return [
            'cityId' => $this->cityId->value,
            'cityName' => $this->cityName,
            'date' => $this->date->toIsoString(),
            'minTemp' => $this->minTemp,
            'maxTemp' => $this->maxTemp,
            'precipitation' => $this->precipitation,
        ];
    }
}
