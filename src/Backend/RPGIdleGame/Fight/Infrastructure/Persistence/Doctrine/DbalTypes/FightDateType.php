<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightDate;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractDatetimeType;

final class FightDateType extends AbstractDatetimeType
{
    protected function mappedClass(): string
    {
        return FightDate::class;
    }
}
