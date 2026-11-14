<?php

declare(strict_types=1);

namespace ADS\TakeHome\Application\Weather\Exceptions;

use ADS\TakeHome\Application\Exceptions\NotFoundException;

class RegionNotFoundException extends NotFoundException
{
    public function __construct(string $region)
    {
        parent::__construct("Region $region not found");
    }
}
