<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\ValueObject;

abstract class StringValueObject
{
    public function __construct(
        protected readonly string $value
    ) {
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $other->value() === $this->value;
    }
}
