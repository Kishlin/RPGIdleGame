<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Bus\Command;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Throwable;

/**
 * Adapter to the Symfony/Messenger bus used as command bus.
 * It does make sure at least one handler was invoked, but only returns the result of the last one invoked.
 * It's a global rule that any Command must invoke only one Handler, which usually lives in the same namespace.
 */
final class InMemoryCommandBusUsingSymfony implements CommandBus
{
    public function __construct(
        private MessageBus $commandBus
    ) {
    }

    /**
     * @throws Throwable
     */
    public function execute(Command $command): ?object
    {
        try {
            $stamp = $this->commandBus->dispatch($command)->last(HandledStamp::class);
            assert($stamp instanceof HandledStamp);

            $result = $stamp->getResult();
            assert(null === $result || is_object($result));

            return $result;
        } catch (NoHandlerForMessageException) {
            throw new CommandNotRegisteredError($command);
        } catch (HandlerFailedException $error) {
            throw $error->getPrevious() ?? $error;
        }
    }
}
