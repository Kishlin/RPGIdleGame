<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Account\Domain\Account;

use Kishlin\Backend\Account\Domain\Account;
use Kishlin\Backend\Account\Domain\AccountCreatedDomainEvent;
use Kishlin\Backend\Account\Domain\ValueObject\AccountEmail;
use Kishlin\Backend\Account\Domain\ValueObject\AccountId;
use Kishlin\Backend\Account\Domain\ValueObject\AccountPassword;
use Kishlin\Backend\Account\Domain\ValueObject\AccountSalt;
use Kishlin\Backend\Account\Domain\ValueObject\AccountUsername;
use Kishlin\Tests\Backend\IsolatedTests\Account\Domain\Account\Constraint\AccountIsActiveConstraint;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Account\Domain\Account
 */
final class AccountTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id       = new AccountId('51cefa3e-c223-469e-a23c-61a32e4bf048');
        $username = new AccountUsername('User');
        $email    = new AccountEmail('user@example.com');
        $password = new AccountPassword('password');
        $salt     = new AccountSalt('salt');

        $account = Account::createActiveAccount($id, $username, $password, $email, $salt);

        self::assertAccountIsActive($account);

        self::assertItRecordedDomainEvents($account, new AccountCreatedDomainEvent($id));
    }

    public static function assertAccountIsActive(Account $account): void
    {
        self::assertThat($account, new AccountIsActiveConstraint());
    }
}
