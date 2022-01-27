<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject;

use Kishlin\Backend\RPGIdleGame\Character\Domain\PointsCanOnlyBeIncreasedException;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;

final class CharacterAttack extends PositiveIntValueObject
{
    public function addAttackPoints(int $attackPointsToAdd): self
    {
        if (0 > $attackPointsToAdd) {
            throw new PointsCanOnlyBeIncreasedException();
        }

        return new self($this->value + $attackPointsToAdd);
    }
}
