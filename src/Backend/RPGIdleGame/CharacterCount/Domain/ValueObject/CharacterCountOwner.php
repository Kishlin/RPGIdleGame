<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class CharacterCountOwner extends UuidValueObject
{
    public static function fromOther(UuidValueObject $other): self
    {
        return new self($other->value);
    }
}
