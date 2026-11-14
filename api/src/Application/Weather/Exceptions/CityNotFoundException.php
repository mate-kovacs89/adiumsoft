<?php

declare(strict_types=1);

namespace ADS\TakeHome\Application\Weather\Exceptions;

use ADS\TakeHome\Application\Exceptions\NotFoundException;
use ADS\TakeHome\Domain\Weather\CityId;

class CityNotFoundException extends NotFoundException
{
    public function __construct(CityId $cityId)
    {
        parent::__construct("City with id {$cityId->value} not found");
    }
}
