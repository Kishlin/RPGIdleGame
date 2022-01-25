<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\ValueObject;

use Stringable;

abstract class UuidValueObject implements Stringable
{
    private const VALID_PATTERN = '\A[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[1-5]{1}[0-9A-Fa-f]{3}-[ABab89]{1}[0-9A-Fa-f]{3}-[0-9A-Fa-f]{12}\z';

    public function __construct(
        protected string $value
    ) {
        $this->ensureIsValid($this->value);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $other->value() === $this->value;
    }

    private function ensureIsValid(string $value): void
    {
        if (false === $this->isAValidUuid($value)) {
            throw new \InvalidArgumentException('The given value is not a valid Uuid.');
        }
    }

    private function isAValidUuid(string $value): bool
    {
        return 1 === preg_match('/' . self::VALID_PATTERN . '/Dms', $value);
    }
}
