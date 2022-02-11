<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterHealth;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractIntegerType;

final class CharacterHealthType extends AbstractIntegerType
{
    protected function mappedClass(): string
    {
        return CharacterHealth::class;
    }
}
