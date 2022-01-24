<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Bus\Event;

use Kishlin\Backend\Shared\Domain\Bus\Event\DomainEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBus;

/**
 * Adapter to the Symfony/Messenger bus used an event dispatcher.
 * No exception will be raised if an event does not trigger any subscriber.
 * An event can trigger any number of subscribers.
 */
final class InMemoryEventDispatcherUsingSymfony implements EventDispatcher
{
    public function __construct(
        private MessageBus $eventBus
    ) {
    }

    public function dispatch(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            try {
                $this->eventBus->dispatch($event);
            } catch (NoHandlerForMessageException) {
            }
        }
    }
}
