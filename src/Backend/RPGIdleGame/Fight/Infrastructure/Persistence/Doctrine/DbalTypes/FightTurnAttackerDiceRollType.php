<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightTurnAttackerDiceRoll;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractIntegerType;

final class FightTurnAttackerDiceRollType extends AbstractIntegerType
{
    protected function mappedClass(): string
    {
        return FightTurnAttackerDiceRoll::class;
    }
}
