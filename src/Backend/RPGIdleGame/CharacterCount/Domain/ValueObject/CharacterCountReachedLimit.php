<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;

final class CharacterCountReachedLimit extends BoolValueObject
{
    public function flagLimitAsReached(): void
    {
        $this->value = true;
    }

    public function limitIsNotReachedAnymore(): void
    {
        $this->value = false;
    }
}
