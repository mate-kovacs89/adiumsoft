<?php

declare(strict_types=1);

namespace ADS\TakeHome\Domain\Weather;

use InvalidArgumentException;

final readonly class DateRange
{
    /**
     * @throws InvalidArgumentException
     */
    public function __construct(
        public CalendarDate $from,
        public CalendarDate $to
    ) {
        if ($from->isAfter($to)) {
            throw new InvalidArgumentException(
                'From date cannot be greater than To date.'
            );
        }
    }
}
