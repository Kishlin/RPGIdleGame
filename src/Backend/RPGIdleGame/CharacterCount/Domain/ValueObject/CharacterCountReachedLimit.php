<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;

final class CharacterCountReachedLimit extends BoolValueObject
{
    public function flagLimitAsReached(): self
    {
        return new self(true);
    }

    public function limitIsNotReachedAnymore(): self
    {
        return new self(false);
    }
}
