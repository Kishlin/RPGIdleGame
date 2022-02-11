<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\CharacterCount\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountReachedLimit;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractBooleanType;

final class CharacterCountReachedLimitType extends AbstractBooleanType
{
    protected function mappedClass(): string
    {
        return CharacterCountReachedLimit::class;
    }
}
