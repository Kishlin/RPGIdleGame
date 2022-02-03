<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\Account\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\Account\Infrastructure\Persistence\Doctrine\AccountWithEmailRepository;
use Kishlin\Tests\Backend\Tools\Provider\AccountProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Account\Infrastructure\Persistence\Doctrine\AccountWithEmailRepository
 */
final class AccountWithEmailRepositoryTest extends RepositoryContractTestCase
{
    /**
     * @throws Exception
     */
    public function testItCanDetectTheEmailIsAlreadyUsed(): void
    {
        $repository   = new AccountWithEmailRepository(self::entityManager());
        $account      = AccountProvider::activeAccount();
        $accountEmail = $account->email();

        self::assertFalse($repository->thereAlreadyIsAnAccountWithEmail($accountEmail));

        $this->loadFixtures($account);

        self::assertTrue($repository->thereAlreadyIsAnAccountWithEmail($accountEmail));
    }
}
