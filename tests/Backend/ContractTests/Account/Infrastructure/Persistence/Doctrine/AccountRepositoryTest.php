<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\Account\Infrastructure\Persistence\Doctrine;

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

        $savedAccount = $repository->findOneById($account->accountId());

        self::assertSame($savedAccount, $account);
    }
}
