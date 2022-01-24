<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Domain;

use InvalidArgumentException;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;

final class AccountEmail extends StringValueObject
{
    public function __construct(string $value)
    {
        $this->ensureIsAValidEmail($value);

        parent::__construct($value);
    }

    private function ensureIsAValidEmail(string $value): void
    {
        if (false === $this->valueIsAValidEmail($value)) {
            throw new InvalidArgumentException('The given value is not a valid email address.');
        }
    }

    private function valueIsAValidEmail(string $value): bool
    {
        return false !== filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}
