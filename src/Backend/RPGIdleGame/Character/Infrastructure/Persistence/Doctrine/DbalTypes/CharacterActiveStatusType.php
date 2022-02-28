<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterActiveStatus;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractBooleanType;

final class CharacterActiveStatusType extends AbstractBooleanType
{
    protected function mappedClass(): string
    {
        return CharacterActiveStatus::class;
    }
}
