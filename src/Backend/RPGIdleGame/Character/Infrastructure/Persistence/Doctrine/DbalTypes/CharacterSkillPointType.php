<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterSkillPoint;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractIntegerType;

final class CharacterSkillPointType extends AbstractIntegerType
{
    protected function mappedClass(): string
    {
        return CharacterSkillPoint::class;
    }
}
