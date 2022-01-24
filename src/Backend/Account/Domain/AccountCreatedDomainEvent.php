<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Domain;

use Kishlin\Backend\Shared\Domain\Bus\Event\DomainEvent;

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

    public function username(): AccountUsername
    {
        return $this->accountUsername;
    }

    public function email(): AccountEmail
    {
        return $this->accountEmail;
    }
}
