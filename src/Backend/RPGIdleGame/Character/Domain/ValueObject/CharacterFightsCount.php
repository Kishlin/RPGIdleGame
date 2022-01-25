<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;

final class CharacterFightsCount extends PositiveIntValueObject
{
    public function tookPartInAFight(): self
    {
        return new self($this->value + 1);
    }
}
