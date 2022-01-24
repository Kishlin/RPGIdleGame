<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\ValueObject;

use InvalidArgumentException;

abstract class StrictlyPositiveIntValueObject extends IntValueObject
{
    public function __construct(int $value)
    {
        $this->ensureIsStrictlyPositive($value);

        parent::__construct($value);
    }

    private function ensureIsStrictlyPositive(int $value): void
    {
        if (0 >= $value) {
            throw new InvalidArgumentException("Given value {$value} is not positive.");
        }
    }
}
