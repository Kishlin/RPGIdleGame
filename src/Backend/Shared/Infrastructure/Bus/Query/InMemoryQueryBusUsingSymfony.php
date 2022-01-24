<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Bus\Query;

use Kishlin\Backend\Shared\Domain\Bus\Query\Query;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Throwable;

/**
 * Adapter to the Symfony/Messenger bus used as query bus.
 * It does make sure at least one handler was invoked, but only returns the result of the last one invoked.
 * It's a global rule that any Query must invoke only one Handler, which usually lives in the same namespace.
 */
final class InMemoryQueryBusUsingSymfony implements QueryBus
{
    public function __construct(
        private MessageBus $queryBus
    ) {
    }

    /**
     * @throws Throwable
     */
    public function ask(Query $query): ?Response
    {
        try {
            $stamp = $this->queryBus->dispatch($query)->last(HandledStamp::class);
            assert($stamp instanceof HandledStamp);

            $result = $stamp->getResult();
            assert(null === $result || $result instanceof Response);

            return $result;
        } catch (NoHandlerForMessageException) {
            throw new QueryNotRegisteredError($query);
        } catch (HandlerFailedException $error) {
            throw $error->getPrevious() ?? $error;
        }
    }
}
