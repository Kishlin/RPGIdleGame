<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;

final class CharacterCountValue extends PositiveIntValueObject
{
    public function increment(): void
    {
        ++$this->value;
    }

    public function decrement(): void
    {
        --$this->value;
    }

    public function hasReachedLimit(int $limitValue): bool
    {
        return $this->value >= $limitValue;
    }
}
