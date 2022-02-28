<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterCreationDate;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractDatetimeType;

final class CharacterCreationDateType extends AbstractDatetimeType
{
    protected function mappedClass(): string
    {
        return CharacterCreationDate::class;
    }
}
