<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\Exception\InvalidValueException;

abstract class StrictlyPositiveIntValueObject extends IntValueObject
{
    protected function ensureIsValid(int $value): void
    {
        $this->ensureIsStrictlyPositive($value);
    }

    private function ensureIsStrictlyPositive(int $value): void
    {
        if (0 >= $value) {
            throw new InvalidValueException("Given value {$value} is not strictly positive.");
        }
    }
}
