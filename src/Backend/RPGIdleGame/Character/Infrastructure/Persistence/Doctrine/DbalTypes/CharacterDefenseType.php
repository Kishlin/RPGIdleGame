<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterDefense;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractIntegerType;

final class CharacterDefenseType extends AbstractIntegerType
{
    protected function mappedClass(): string
    {
        return CharacterDefense::class;
    }
}
