<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Monitoring\Probe;

final class ApplicationProbe implements Probe
{
    public function __construct(
        private string $appName
    ) {
    }

    public function name(): string
    {
        return $this->appName;
    }

    public function isAlive(): bool
    {
        return true;
    }
}
