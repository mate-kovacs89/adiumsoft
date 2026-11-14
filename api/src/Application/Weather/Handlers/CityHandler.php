<?php

declare(strict_types=1);

namespace ADS\TakeHome\Application\Weather\Handlers;

use ADS\TakeHome\Application\Weather\DTOs\CityDTO;
use ADS\TakeHome\Application\Weather\Repositories\WeatherRepositoryInterface;

final readonly class CityHandler
{
    public function __construct(
        private WeatherRepositoryInterface $weatherRepository,
    ) {
    }

    /**
     * @return list<CityDTO>
     */
    public function handle(): array
    {
        return $this->weatherRepository->findAllCities();
    }
}
