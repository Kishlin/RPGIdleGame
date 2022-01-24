<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\Account\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\ORMException;
use Kishlin\Backend\Account\Domain\Account;
use Kishlin\Backend\Account\Domain\AccountEmail;
use Kishlin\Backend\Account\Infrastructure\Persistence\Doctrine\AccountRepository;
use Kishlin\Tests\Backend\Tools\Provider\AccountProvider;
use Kishlin\Tests\Backend\Tools\ReflectionHelper;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;
use ReflectionException;

/**
 * @internal
 * @covers \Kishlin\Backend\Account\Infrastructure\Persistence\Doctrine\AccountRepository
 */
final class AccountRepositoryTest extends RepositoryContractTestCase
{
    /**
     * @throws Exception|ORMException|ReflectionException
     */
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
     * @throws Exception|ORMException|ReflectionException
     */
    public function testItCanDetectTheEmailIsAlreadyUsed(): void
    {
        $account    = AccountProvider::activeAccount();
        $repository = new AccountRepository(self::entityManager());

        $accountEmail = ReflectionHelper::propertyValue($account, 'accountEmail');
        assert($accountEmail instanceof AccountEmail);

        self::assertFalse($repository->thereAlreadyIsAnAccountWithEmail($accountEmail));

        $repository->save($account);

        self::assertTrue($repository->thereAlreadyIsAnAccountWithEmail($accountEmail));
    }
}
