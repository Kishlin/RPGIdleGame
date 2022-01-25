<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject;

use DateTimeImmutable;
use Kishlin\Backend\Shared\Domain\ValueObject\DateTimeValueObject;

final class CharacterRestingUntil extends DateTimeValueObject
{
    public static function unavailableUntil(DateTimeImmutable $dateOfNewAvailabiliy): self
    {
        return new self($dateOfNewAvailabiliy);
    }
}
