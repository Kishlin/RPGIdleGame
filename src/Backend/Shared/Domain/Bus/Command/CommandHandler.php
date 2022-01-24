<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\Bus\Command;

/**
 * CommandHandlers must have an __invoke() method that takes one parameter, an instance of \Kishlin\Backend\Shared\Domain\Bus\Command\Command
 * The method can either return null, or a value of type <mixed> that will be returned by the CommandBus to the client.
 *
 * @see \Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus
 */
interface CommandHandler
{
}
