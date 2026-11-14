<?php

declare(strict_types=1);

namespace ADS\TakeHome\Framework\Validation;

final class Validator implements ValidatorContract
{
    /**
     * {@inheritDoc}
     */
    public function validate(array $data, array $rules): ValidationResult
    {
        $errors = [];

        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;

            $error = $rule($value, $data);

            if ($error !== null) {
                $errors[$field][] = $error;
            }
        }

        return new ValidationResult($errors);
    }
}
