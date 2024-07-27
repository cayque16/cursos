<?php

namespace Core\SeedWork\Domain\Validators;

use Core\SeedWork\Domain\Exceptions\Exception\EntityValidationException;

class DomainValidation
{
    public static function notNull(string $value = null, string $customMessage = null)
    {
        if (empty($value)) {
            throw new EntityValidationException($customMessage ?? 'Should not be empty');
        }
    }

    public static function strMaxLength(string $value, int $length = 255, string $customMessage = null)
    {
        if (strlen($value) >= $length) {
            throw new EntityValidationException($customMessage ?? "The value must not be greater than {$length} characters");
        }
    }
    
    public static function strMinLength(string $value, int $length = 3, string $customMessage = null)
    {
        if (strlen($value) <= $length) {
            throw new EntityValidationException($customMessage ?? "The value must be at least {$length} characters");
        }
    }
    
    public static function strCanNullAndMaxLength(string $value, int $length = 255, string $customMessage = null)
    {
        if (!empty($value) && strlen($value) > $length) {
            throw new EntityValidationException($customMessage ?? "The value must not be greater than {$length} characters");
        }
    }
}
