<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterRestingUntil;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractDatetimeType;

final class CharacterRestingUntilType extends AbstractDatetimeType
{
    protected function mappedClass(): string
    {
        return CharacterRestingUntil::class;
    }
}
