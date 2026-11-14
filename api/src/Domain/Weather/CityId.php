<?php

declare(strict_types=1);

namespace ADS\TakeHome\Domain\Weather;

use InvalidArgumentException;

final readonly class CityId
{
    private function __construct(
        public int $value,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromInt(int $value): self
    {
        if ($value <= 0) {
            throw new InvalidArgumentException("Invalid city id: {$value}");
        }

        return new self($value);
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromString(string $value): self
    {
        $id = filter_var($value, FILTER_VALIDATE_INT);

        if ($id === false) {
            throw new InvalidArgumentException("Invalid city id: {$value}");
        }

        return self::fromInt($id);
    }
}
