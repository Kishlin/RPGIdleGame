<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject;

use Kishlin\Backend\RPGIdleGame\Character\Domain\PointsCanOnlyBeIncreasedException;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;

final class CharacterHealth extends StrictlyPositiveIntValueObject
{
    /**
     * @throws PointsCanOnlyBeIncreasedException
     */
    public function addHealthPoints(int $healthPointsToAdd): self
    {
        if (0 > $healthPointsToAdd) {
            throw new PointsCanOnlyBeIncreasedException();
        }

        return new self($this->value + $healthPointsToAdd);
    }
}
