<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Domain;

use Kishlin\Backend\Account\Domain\ValueObject\AccountId;

/**
 * Saves, and finds, Account entities.
 * To get read-only entities for internal use, use the Read Model gateway.
 * To get entity views, intended to be shown to a client, use the View gateway.
 * Finding entities should only be allowed to any services which intend to update an entity.
 */
interface AccountGateway
{
    public function save(Account $account): void;

    public function findOneById(AccountId $accountId): ?Account;
}
