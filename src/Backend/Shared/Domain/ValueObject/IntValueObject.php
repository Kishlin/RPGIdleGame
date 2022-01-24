<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\ValueObject;

abstract class IntValueObject
{
    public function __construct(
        protected int $value
    ) {
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $other->value() === $this->value;
    }
}
