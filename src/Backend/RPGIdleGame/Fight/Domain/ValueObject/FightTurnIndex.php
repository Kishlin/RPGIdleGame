<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;

final class FightTurnIndex extends PositiveIntValueObject
{
    public function nextTurn(): self
    {
        return new self($this->value + 1);
    }
}
