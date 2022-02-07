<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;

final class CharacterStatsFightsCount extends PositiveIntValueObject
{
    public function increment(): self
    {
        return new self($this->value + 1);
    }
}
