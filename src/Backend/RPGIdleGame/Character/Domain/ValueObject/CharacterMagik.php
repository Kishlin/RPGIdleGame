<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject;

use Kishlin\Backend\RPGIdleGame\Character\Domain\PointsCanOnlyBeIncreasedException;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;

final class CharacterMagik extends PositiveIntValueObject
{
    /**
     * @throws PointsCanOnlyBeIncreasedException
     */
    public function addMagikPoints(int $magikPointsToAdd): self
    {
        if (0 > $magikPointsToAdd) {
            throw new PointsCanOnlyBeIncreasedException();
        }

        return new self($this->value + $magikPointsToAdd);
    }
}
