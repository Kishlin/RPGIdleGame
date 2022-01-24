<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Randomness;

use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Ramsey\Uuid\Uuid;

final class UuidGeneratorUsingRamsey implements UuidGenerator
{
    public function uuid4(): string
    {
        return Uuid::uuid4()->toString();
    }
}
