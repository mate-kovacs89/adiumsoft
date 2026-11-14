<?php

declare(strict_types=1);

namespace ADS\TakeHome\Domain\Weather;

use DateTimeImmutable;
use InvalidArgumentException;

final readonly class CalendarDate
{
    private const ISO_FORMAT = 'Y-m-d';

    private function __construct(
        public DateTimeImmutable $value,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromString(string $value): self
    {
        return self::fromFormat(self::ISO_FORMAT, $value);
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromFormat(string $format, string $value): self
    {
        $date = DateTimeImmutable::createFromFormat('!' . $format, $value);

        if ($date === false || $date->format($format) !== $value) {
            throw new InvalidArgumentException("Invalid date: {$value}");
        }

        return new self($date);
    }

    public function isAfter(self $other): bool
    {
        return $this->value > $other->value;
    }

    public function format(string $format): string
    {
        return $this->value->format($format);
    }

    public function toIsoString(): string
    {
        return $this->value->format(self::ISO_FORMAT);
    }
}
