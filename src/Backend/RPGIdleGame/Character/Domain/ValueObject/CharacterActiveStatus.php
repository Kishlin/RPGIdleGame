<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;

final class CharacterActiveStatus extends BoolValueObject
{
    public function flagAsInactive(): self
    {
        return new self(false);
    }

    public function isActive(): bool
    {
        return $this->value();
    }
}
