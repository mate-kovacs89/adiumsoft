<?php

declare(strict_types=1);

namespace ADS\TakeHome\Application\Weather\Handlers;

use ADS\TakeHome\Application\Weather\DTOs\RegionDailyWeatherDTO;
use ADS\TakeHome\Application\Weather\DTOs\RegionWeatherQueryDTO;
use ADS\TakeHome\Application\Weather\Exceptions\RegionNotFoundException;
use ADS\TakeHome\Application\Weather\Repositories\WeatherRepositoryInterface;

final readonly class RegionWeatherQueryHandler
{
    public function __construct(
        private WeatherRepositoryInterface $weatherRepository,
    ) {
    }

    /**
     * @return list<RegionDailyWeatherDTO>
     */
    public function handle(RegionWeatherQueryDTO $query): array
    {
        $isExist = $this->weatherRepository->regionExists($query->region);
        if (!$isExist) {
            throw new RegionNotFoundException($query->region);
        }

        return $this->weatherRepository->findRegionWeather(
            $query->region,
            $query->dateRange,
        );
    }
}
