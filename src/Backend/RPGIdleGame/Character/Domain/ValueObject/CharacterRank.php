<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;

final class CharacterRank extends StrictlyPositiveIntValueObject
{
    public function rankUp(): void
    {
        ++$this->value;
    }

    public function rankDownIfItCan(): void
    {
        if (1 < $this->value) {
            --$this->value;
        }
    }
}
