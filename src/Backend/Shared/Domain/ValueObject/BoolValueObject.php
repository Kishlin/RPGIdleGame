<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\ValueObject;

abstract class BoolValueObject
{
    public function __construct(
        protected bool $value
    ) {
    }

    public function value(): bool
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $other->value() === $this->value;
    }
}
