<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Domain;

use Kishlin\Backend\Account\Domain\ValueObject\AccountEmail;
use Kishlin\Backend\Account\Domain\ValueObject\AccountId;
use Kishlin\Backend\Account\Domain\ValueObject\AccountUsername;
use Kishlin\Backend\Shared\Domain\Bus\Event\DomainEvent;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class AccountCreatedDomainEvent extends DomainEvent
{
    public function __construct(
        AccountId $accountId,
        private AccountUsername $accountUsername,
        private AccountEmail $accountEmail,
    ) {
        parent::__construct($accountId);
    }

    public static function eventName(): string
    {
        return 'account.created';
    }

    public function accountId(): UuidValueObject
    {
        return $this->aggregateUuid();
    }

    public function username(): AccountUsername
    {
        return $this->accountUsername;
    }

    public function email(): AccountEmail
    {
        return $this->accountEmail;
    }
}
