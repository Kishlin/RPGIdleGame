<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightTurnDamageDealt;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractIntegerType;

final class FightTurnDamageDealtType extends AbstractIntegerType
{
    protected function mappedClass(): string
    {
        return FightTurnDamageDealt::class;
    }
}
