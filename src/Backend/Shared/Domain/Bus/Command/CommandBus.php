<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\Bus\Command;

interface CommandBus
{
    public function execute(Command $command): mixed;
}
