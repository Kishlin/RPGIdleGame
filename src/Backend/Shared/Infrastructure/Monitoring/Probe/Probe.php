<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Monitoring\Probe;

/**
 * Shows the status of a service.
 * Any service that wants to show its status must have a name, in addition to showing its Alive status.
 */
interface Probe
{
    public function name(): string;

    public function isAlive(): bool;
}
