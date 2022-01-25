<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\CharacterCount\Application\OnAccountCreated;

use Kishlin\Backend\Account\Domain\AccountCreatedDomainEvent;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCount;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCountGateway;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountOwner;
use Kishlin\Backend\Shared\Domain\Bus\Event\DomainEventSubscriber;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;

final class CharacterCountForOwnerCreator implements DomainEventSubscriber
{
    public function __construct(
        private CharacterCountGateway $characterCountGateway,
        private EventDispatcher $eventDispatcher,
    ) {
    }

    public function __invoke(AccountCreatedDomainEvent $accountCreatedDomainEvent): void
    {
        $createdAccountId = CharacterCountOwner::fromOther($accountCreatedDomainEvent->accountId());
        $characterCount   = CharacterCount::createForOwner($createdAccountId);

        $this->characterCountGateway->save($characterCount);

        $this->eventDispatcher->dispatch(...$characterCount->pullDomainEvents());
    }

    public static function subscribedTo(): array
    {
        return [
            AccountCreatedDomainEvent::class,
        ];
    }
}
