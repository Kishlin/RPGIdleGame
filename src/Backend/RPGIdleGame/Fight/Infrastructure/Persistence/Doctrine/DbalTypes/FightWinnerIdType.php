<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightWinnerId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractNullableUuidType;

final class FightWinnerIdType extends AbstractNullableUuidType
{
    protected function mappedClass(): string
    {
        return FightWinnerId::class;
    }
}
