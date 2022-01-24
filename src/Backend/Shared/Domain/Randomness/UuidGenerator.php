<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\Randomness;

interface UuidGenerator
{
    public function uuid4(): string;
}
