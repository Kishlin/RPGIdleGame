<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Time;

use DateTimeImmutable;
use Kishlin\Backend\Shared\Domain\Time\Clock;

final class SystemClock implements Clock
{
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable(datetime: 'now');
    }
}
