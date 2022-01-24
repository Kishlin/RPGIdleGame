<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Provider;

use Kishlin\Backend\Account\Domain\Account;
use Kishlin\Backend\Account\Domain\AccountEmail;
use Kishlin\Backend\Account\Domain\AccountId;
use Kishlin\Backend\Account\Domain\AccountPassword;
use Kishlin\Backend\Account\Domain\AccountUsername;

final class AccountProvider
{
    public static function activeAccount(): Account
    {
        return Account::createActiveAccount(
            new AccountId('3cea2acd-b525-4984-ba48-51642b7e8db1'),
            new AccountUsername('User'),
            new AccountPassword('password'),
            new AccountEmail('fresh.account@example.com'),
        );
    }
}
