<?php

declare(strict_types=1);

namespace ADS\TakeHome\Application\Weather\Handlers;

use ADS\TakeHome\Application\Weather\DTOs\CityDailyWeatherDTO;
use ADS\TakeHome\Application\Weather\DTOs\CityWeatherQueryDTO;
use ADS\TakeHome\Application\Weather\Exceptions\CityNotFoundException;
use ADS\TakeHome\Application\Weather\Repositories\WeatherRepositoryInterface;

final readonly class CityWeatherQueryHandler
{
    public function __construct(
        private WeatherRepositoryInterface $weatherRepository,
    ) {
    }

    /**
     * @return list<CityDailyWeatherDTO>
     */
    public function handle(CityWeatherQueryDTO $query): array
    {
        $isExist = $this->weatherRepository->cityExists($query->cityId);
        if (!$isExist) {
            throw new CityNotFoundException($query->cityId);
        }

        return $this->weatherRepository->findCityWeather(
            $query->cityId,
            $query->dateRange,
        );
    }
}
