<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Time;

use DateTimeImmutable;
use Kishlin\Backend\Shared\Domain\Time\Clock;

/**
 * @internal used for tests controlling time
 */
final class FrozenClock implements Clock
{
    public function __construct(
        private DateTimeImmutable $frozenTime,
    ) {
    }

    public function now(): DateTimeImmutable
    {
        return $this->frozenTime;
    }
}
