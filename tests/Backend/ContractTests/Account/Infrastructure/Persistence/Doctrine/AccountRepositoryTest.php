<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\Account\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\Account\Domain\Account;
use Kishlin\Backend\Account\Infrastructure\Persistence\Doctrine\AccountRepository;
use Kishlin\Tests\Backend\Tools\Provider\AccountProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Account\Infrastructure\Persistence\Doctrine\AccountRepository
 */
final class AccountRepositoryTest extends RepositoryContractTestCase
{
    public function testItCanSaveAnAccount(): void
    {
        $account = AccountProvider::activeAccount();

        $repository = new AccountRepository(self::entityManager());

        $repository->save($account);

        /** @var Account $savedAccount */
        $savedAccount = self::entityManager()->getRepository(Account::class)->find($account->accountId());

        self::assertSame($savedAccount, $account);
    }

    /**
     * @depends testItCanSaveAnAccount
     *
     * @throws Exception
     */
    public function testItCanDetectTheEmailIsAlreadyUsed(): void
    {
        $repository   = new AccountRepository(self::entityManager());
        $account      = AccountProvider::activeAccount();
        $accountEmail = $account->accountEmail();

        self::assertFalse($repository->thereAlreadyIsAnAccountWithEmail($accountEmail));

        $repository->save($account);

        self::assertTrue($repository->thereAlreadyIsAnAccountWithEmail($accountEmail));
    }
}
