<?php

namespace ADS\TakeHome;

include __DIR__ . "/../vendor/autoload.php";

use ADS\TakeHome\Application\Weather\Factories\CityWeatherQueryFactory;
use ADS\TakeHome\Application\Weather\Factories\RegionWeatherQueryFactory;
use ADS\TakeHome\Application\Weather\Handlers\CityHandler;
use ADS\TakeHome\Application\Weather\Handlers\CityWeatherQueryHandler;
use ADS\TakeHome\Application\Weather\Handlers\RegionWeatherQueryHandler;
use ADS\TakeHome\Application\Weather\Validations\CityWeatherQueryValidation;
use ADS\TakeHome\Application\Weather\Validations\RegionWeatherQueryValidation;
use ADS\TakeHome\Controllers\ExampleController;
use ADS\TakeHome\Controllers\WeatherController;
use ADS\TakeHome\Framework\Database\MySQL;
use ADS\TakeHome\Framework\Routing\Router;
use ADS\TakeHome\Framework\Validation\Validator;
use ADS\TakeHome\Infrastructure\Persistence\MySQL\MySQLWeatherRepository;

$router = new Router();
$mysql = new MySQL("db", "weather", "root", "root");

$weatherRepository = new MySQLWeatherRepository($mysql);

$validator = new Validator();
$cityQueryValidation = new CityWeatherQueryValidation();
$regionQueryValidation = new RegionWeatherQueryValidation();
$cityWeatherQueryFactory = new CityWeatherQueryFactory();
$regionWeatherQueryFactory = new RegionWeatherQueryFactory();
$cityWeatherQueryHandler = new CityWeatherQueryHandler($weatherRepository);
$regionWeatherQueryHandler = new RegionWeatherQueryHandler($weatherRepository);
$cityHandler = new CityHandler($weatherRepository);

$helloController = new ExampleController(greeting: "Hello", db: $mysql);
$weatherController = new WeatherController(
    $validator,
    $cityQueryValidation,
    $regionQueryValidation,
    $cityWeatherQueryFactory,
    $cityWeatherQueryHandler,
    $regionWeatherQueryFactory,
    $regionWeatherQueryHandler,
    $cityHandler
);

$router->addRoute("GET", "/hello", $helloController->sayHello(...));
$router->addRoute("GET", "/budapest", $helloController->getBudapestMaxTempCount(...));

$router->addRoute("GET", "/weathers/cities", $weatherController->getCityWeather(...));
$router->addRoute("GET", "/weathers/regions", $weatherController->getRegionWeather(...));
$router->addRoute("GET", "/cities", $weatherController->getCities(...));

$router->handleRequest();
