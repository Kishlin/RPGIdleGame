<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightTurnDefenderDefense;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractIntegerType;

final class FightTurnDefenderDefenseType extends AbstractIntegerType
{
    protected function mappedClass(): string
    {
        return FightTurnDefenderDefense::class;
    }
}
