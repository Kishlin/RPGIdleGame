<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject;

use Kishlin\Backend\RPGIdleGame\Character\Domain\PointsCanOnlyBeIncreasedException;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;

final class CharacterDefense extends PositiveIntValueObject
{
    public function addDefensePoints(int $defensePointsToAdd): self
    {
        if (0 > $defensePointsToAdd) {
            throw new PointsCanOnlyBeIncreasedException();
        }

        return new self($this->value + $defensePointsToAdd);
    }
}
