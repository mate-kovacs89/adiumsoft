<?php

declare(strict_types=1);

namespace ADS\TakeHome\Application\Weather\Validations;

use ADS\TakeHome\Domain\Weather\CalendarDate;
use ADS\TakeHome\Domain\Weather\CityId;
use ADS\TakeHome\Domain\Weather\DateRange;
use InvalidArgumentException;

final class CityWeatherQueryValidation
{
    /**
     * @return array<string, callable>
     */
    public function rules(): array
    {
        return [
            'cityId' => static function ($value): ?string {
                if (!is_string($value) || !self::passes(static fn () => CityId::fromString($value))) {
                    return 'City ID must be a positive integer.';
                }

                return null;
            },
            'from' => static function ($value): ?string {
                if (!self::isValidDate($value)) {
                    return 'Start date must have YYYY-MM-DD format.';
                }

                return null;
            },
            'to' => static function ($value): ?string {
                if (!self::isValidDate($value)) {
                    return 'End date must have YYYY-MM-DD format.';
                }

                return null;
            },
            'dateRange' => static function ($value, array $data): ?string {
                $from = $data['from'] ?? null;
                $to = $data['to'] ?? null;

                if (!self::isValidDate($from) || !self::isValidDate($to)) {
                    return null;
                }

                if (!self::passes(static fn () => new DateRange(CalendarDate::fromString($from), CalendarDate::fromString($to)))) {
                    return 'Start date cannot be later than end date.';
                }

                return null;
            },
        ];
    }

    /**
     * @phpstan-assert-if-true string $value
     */
    private static function isValidDate(mixed $value): bool
    {
        return is_string($value)
            && self::passes(static fn () => CalendarDate::fromString($value));
    }

    private static function passes(callable $create): bool
    {
        try {
            $create();

            return true;
        } catch (InvalidArgumentException) {
            return false;
        }
    }
}
