<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;

final class CharacterRank extends StrictlyPositiveIntValueObject
{
    public function rankUp(): self
    {
        return new self($this->value + 1);
    }

    public function rankDownIfItCan(): self
    {
        return new self($this->value > 1 ? $this->value - 1 : $this->value);
    }
}
