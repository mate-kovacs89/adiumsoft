<?php

declare(strict_types=1);

namespace ADS\TakeHome\Framework\Validation;

final readonly class ValidationResult
{
    /**
     * @param array<string, string[]> $errors
     */
    public function __construct(public array $errors)
    {

    }

    public function isValid(): bool
    {
        return empty($this->errors);
    }
}
