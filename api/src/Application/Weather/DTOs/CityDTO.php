<?php

declare(strict_types=1);

namespace ADS\TakeHome\Application\Weather\DTOs;

use ADS\TakeHome\Domain\Weather\CityId;
use JsonSerializable;

final readonly class CityDTO implements JsonSerializable
{
    public function __construct(
        public CityId $id,
        public string $name,
        public string $region,
    ) {
    }

    /**
     * @return array{id: int, name: string, region: string}
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id->value,
            'name' => $this->name,
            'region' => $this->region,
        ];
    }
}
