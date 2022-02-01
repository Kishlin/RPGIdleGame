<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\Exception\InvalidValueException;

abstract class PositiveIntValueObject extends IntValueObject
{
    protected function ensureIsValid(int $value): void
    {
        $this->ensureIsPositive($value);
    }

    private function ensureIsPositive(int $value): void
    {
        if (0 > $value) {
            throw new InvalidValueException("Given value {$value} is not positive.");
        }
    }
}
