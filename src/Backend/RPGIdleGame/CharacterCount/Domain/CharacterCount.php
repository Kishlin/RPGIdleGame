<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\CharacterCount\Domain;

use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountOwner;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountReachedLimit;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountValue;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;

final class CharacterCount extends AggregateRoot
{
    public const CHARACTER_LIMIT = 10;

    private function __construct(
        private CharacterCountOwner $characterCountOwner,
        private CharacterCountValue $characterCountValue,
        private CharacterCountReachedLimit $characterCountReachedLimit,
    ) {
    }

    public static function createForOwner(CharacterCountOwner $characterCountOwner): self
    {
        return new self($characterCountOwner, new CharacterCountValue(0), new CharacterCountReachedLimit(false));
    }

    public function incrementOnCharacterCreation(): void
    {
        $this->characterCountValue = $this->characterCountValue->increment();

        if ($this->characterCountValue->hasReachedLimit(self::CHARACTER_LIMIT)) {
            $this->characterCountReachedLimit = $this->characterCountReachedLimit->flagLimitAsReached();
        }
    }

    public function decrementOnCharacterDeletion(): void
    {
        $this->characterCountValue = $this->characterCountValue->decrement();

        $this->characterCountReachedLimit = $this->characterCountReachedLimit->limitIsNotReachedAnymore();
    }

    public function characterCountOwner(): CharacterCountOwner
    {
        return $this->characterCountOwner;
    }

    public function characterCountValue(): CharacterCountValue
    {
        return $this->characterCountValue;
    }

    public function characterCountReachedLimit(): CharacterCountReachedLimit
    {
        return $this->characterCountReachedLimit;
    }
}
