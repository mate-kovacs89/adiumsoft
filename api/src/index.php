<?php

namespace ADS\TakeHome;

include __DIR__ . "/../vendor/autoload.php";

use ADS\TakeHome\Controllers\ExampleController;
use ADS\TakeHome\Framework\Database\MySQL;
use ADS\TakeHome\Framework\Routing\Router;

$router = new Router();

$mysql = new MySQL("db", "weather", "root", "root");
$helloController = new ExampleController(greeting: "Hello", db: $mysql);

$router->addRoute("GET", "/hello", $helloController->sayHello(...));
$router->addRoute("GET", "/budapest", $helloController->getBudapestMaxTempCount(...));
// TODO: többi végpont

$router->handleRequest();
