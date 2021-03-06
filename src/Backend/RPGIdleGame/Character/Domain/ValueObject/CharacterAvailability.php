<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject;

use DateInterval;
use DateTimeImmutable;
use Kishlin\Backend\Shared\Domain\ValueObject\DateTimeValueObject;

final class CharacterAvailability extends DateTimeValueObject
{
    public static function availableAsOf(DateTimeImmutable $dateOfNewAvailabiliy): self
    {
        return new self($dateOfNewAvailabiliy);
    }

    public function oneHourAfter(DateTimeValueObject $other): self
    {
        return new self($other->value->add(new DateInterval('PT1H')));
    }
}
