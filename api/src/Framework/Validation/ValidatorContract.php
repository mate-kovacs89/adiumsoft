<?php

declare(strict_types=1);

namespace ADS\TakeHome\Framework\Validation;

interface ValidatorContract
{
    /**
     * @param array<string, mixed> $data
     * @param array<string, callable> $rules
     */
    public function validate(array $data, array $rules): ValidationResult;
}
