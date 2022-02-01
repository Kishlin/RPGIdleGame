<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\Exception\InvalidValueException;

/**
 * @method string value()
 */
abstract class UuidValueObject extends NullableUuidValueObject
{
    protected function ensureIsValid(?string $value): void
    {
        if (null === $value) {
            throw new InvalidValueException('Value cannot be null.');
        }

        parent::ensureIsValid($value);
    }
}
