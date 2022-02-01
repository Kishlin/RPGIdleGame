<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;

final class FightParticipantHealth extends PositiveIntValueObject
{
    public function removeHealth(int $healthToRemove): self
    {
        return new self(max(0, $this->value - $healthToRemove));
    }

    public function isStillAlive(): bool
    {
        return 0 < $this->value;
    }
}
