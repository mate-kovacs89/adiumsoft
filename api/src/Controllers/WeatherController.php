<?php

declare(strict_types=1);

namespace ADS\TakeHome\Controllers;

use ADS\TakeHome\Application\Exceptions\NotFoundException;
use ADS\TakeHome\Application\Weather\Factories\CityWeatherQueryFactory;
use ADS\TakeHome\Application\Weather\Factories\RegionWeatherQueryFactory;
use ADS\TakeHome\Application\Weather\Handlers\CityHandler;
use ADS\TakeHome\Application\Weather\Handlers\CityWeatherQueryHandler;
use ADS\TakeHome\Application\Weather\Handlers\RegionWeatherQueryHandler;
use ADS\TakeHome\Application\Weather\Validations\CityWeatherQueryValidation;
use ADS\TakeHome\Application\Weather\Validations\RegionWeatherQueryValidation;
use ADS\TakeHome\Controllers\Responses\ApiResponse;
use ADS\TakeHome\Framework\Http\HttpRequest;
use ADS\TakeHome\Framework\Http\JsonHttpResponse;
use ADS\TakeHome\Framework\Validation\ValidatorContract;

class WeatherController
{
    public function __construct(
        private readonly ValidatorContract $validator,
        private readonly CityWeatherQueryValidation $cityWeatherQueryValidation,
        private readonly RegionWeatherQueryValidation $regionWeatherQueryValidation,
        private readonly CityWeatherQueryFactory $cityWeatherQueryFactory,
        private readonly CityWeatherQueryHandler $cityWeatherQueryHandler,
        private readonly RegionWeatherQueryFactory $regionWeatherQueryFactory,
        private readonly RegionWeatherQueryHandler $regionWeatherQueryHandler,
        private readonly CityHandler $cityHandler
    ) {

    }

    public function getCities(HttpRequest $request): JsonHttpResponse
    {
        return ApiResponse::ok($this->cityHandler->handle());
    }

    public function getCityWeather(HttpRequest $request): JsonHttpResponse
    {
        $validationResult = $this->validator->validate(
            $request->query,
            $this->cityWeatherQueryValidation->rules()
        );

        if (!$validationResult->isValid()) {
            return ApiResponse::validationError($validationResult->errors);
        }
        try {
            $result = $this->cityWeatherQueryHandler->handle(
                $this->cityWeatherQueryFactory->create($request->query)
            );

            return ApiResponse::ok($result);
        } catch (NotFoundException $e) {
            return ApiResponse::notFound($e->getMessage());
        }
    }

    public function getRegionWeather(HttpRequest $request): JsonHttpResponse
    {
        $validationResult = $this->validator->validate(
            $request->query,
            $this->regionWeatherQueryValidation->rules()
        );

        if (!$validationResult->isValid()) {
            return ApiResponse::validationError($validationResult->errors);
        }

        try {
            $result = $this->regionWeatherQueryHandler->handle(
                $this->regionWeatherQueryFactory->create($request->query)
            );

            return ApiResponse::ok($result);
        } catch (NotFoundException $e) {
            return ApiResponse::notFound($e->getMessage());
        }
    }

}
