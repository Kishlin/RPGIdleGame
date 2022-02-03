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
        private CharacterCountOwner $owner,
        private CharacterCountValue $count,
        private CharacterCountReachedLimit $reachedLimit,
    ) {
    }

    public static function createForOwner(CharacterCountOwner $owner): self
    {
        return new self($owner, new CharacterCountValue(0), new CharacterCountReachedLimit(false));
    }

    public function incrementOnCharacterCreation(): void
    {
        $this->count = $this->count->increment();

        if ($this->count->hasReachedLimit(self::CHARACTER_LIMIT)) {
            $this->reachedLimit = $this->reachedLimit->flagLimitAsReached();
        }
    }

    public function decrementOnCharacterDeletion(): void
    {
        $this->count = $this->count->decrement();

        $this->reachedLimit = $this->reachedLimit->limitIsNotReachedAnymore();
    }

    public function owner(): CharacterCountOwner
    {
        return $this->owner;
    }

    public function count(): CharacterCountValue
    {
        return $this->count;
    }

    public function reachedLimit(): CharacterCountReachedLimit
    {
        return $this->reachedLimit;
    }
}
