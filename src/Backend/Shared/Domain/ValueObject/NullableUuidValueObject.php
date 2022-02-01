<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\Exception\InvalidValueException;
use Stringable;

abstract class NullableUuidValueObject implements Stringable
{
    private const VALID_PATTERN = '\A[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[1-5]{1}[0-9A-Fa-f]{3}-[ABab89]{1}[0-9A-Fa-f]{3}-[0-9A-Fa-f]{12}\z';

    final public function __construct(
        protected readonly ?string $value
    ) {
        $this->ensureIsValid($this->value);
    }

    public function __toString(): string
    {
        return null === $this->value ? '' : $this->value;
    }

    public static function fromOther(self $other): static
    {
        return new static($other->value());
    }

    public function value(): ?string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $other->value() === $this->value;
    }

    protected function ensureIsValid(?string $value): void
    {
        if (null !== $value && false === $this->isAValidUuid($value)) {
            throw new InvalidValueException('The given value is not a valid Uuid.');
        }
    }

    private function isAValidUuid(string $value): bool
    {
        return 1 === preg_match('/' . self::VALID_PATTERN . '/Dms', $value);
    }
}
