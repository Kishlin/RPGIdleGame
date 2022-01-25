<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\ValueObject;

use DateTimeImmutable;

abstract class DateTimeValueObject
{
    public function __construct(
        protected readonly DateTimeImmutable $value
    ) {
    }

    public function value(): DateTimeImmutable
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return 0 === ($other->value() <=> $this->value);
    }
}
