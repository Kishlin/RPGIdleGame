<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\Bus\Event;

interface EventDispatcher
{
    public function dispatch(DomainEvent ...$domainEvents): void;
}
