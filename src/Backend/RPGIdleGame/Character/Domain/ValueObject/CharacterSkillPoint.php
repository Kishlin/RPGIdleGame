<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject;

use Kishlin\Backend\RPGIdleGame\Character\Domain\NotEnoughSkillPointsException;
use Kishlin\Backend\Shared\Domain\Exception\InvalidValueException;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;

final class CharacterSkillPoint extends PositiveIntValueObject
{
    public function earnASkillPoint(): self
    {
        return new self($this->value + 1);
    }

    /**
     * @throws InvalidValueException|NotEnoughSkillPointsException
     */
    public function removeSkillPoints(int $skillPointsToRemove): self
    {
        if (0 > $skillPointsToRemove) {
            throw new InvalidValueException();
        }

        if ($skillPointsToRemove > $this->value) {
            throw new NotEnoughSkillPointsException();
        }

        return new self($this->value - $skillPointsToRemove);
    }
}
