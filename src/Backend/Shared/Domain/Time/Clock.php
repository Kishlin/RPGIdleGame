<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\Time;

use DateTimeImmutable;

interface Clock
{
    public function now(): DateTimeImmutable;
}
