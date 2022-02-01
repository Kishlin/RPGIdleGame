<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\ValueObject;

abstract class IntValueObject
{
    final public function __construct(
        protected readonly int $value
    ) {
        $this->ensureIsValid($this->value);
    }

    public static function fromOther(self $other): static
    {
        return new static($other->value);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $other->value() === $this->value;
    }

    protected function ensureIsValid(int $value): void
    {
    }
}
