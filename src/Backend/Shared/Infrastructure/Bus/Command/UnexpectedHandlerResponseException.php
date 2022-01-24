<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Bus\Command;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use RuntimeException;

final class UnexpectedHandlerResponseException extends RuntimeException
{
    public function __construct(Command $command)
    {
        $commandClass = $command::class;

        parent::__construct("Failed to get an acceptable response for command of type <{$commandClass}>.");
    }
}
