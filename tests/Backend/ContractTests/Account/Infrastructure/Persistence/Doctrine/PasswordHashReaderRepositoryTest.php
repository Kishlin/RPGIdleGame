<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\Account\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\Account\Domain\ReadModel\AccountDetailsForAuthentication;
use Kishlin\Backend\Account\Infrastructure\Persistence\Doctrine\AccountReaderRepository;
use Kishlin\Tests\Backend\Tools\Provider\AccountProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Account\Infrastructure\Persistence\Doctrine\AccountReaderRepository
 */
final class PasswordHashReaderRepositoryTest extends RepositoryContractTestCase
{
    /**
     * @throws Exception
     */
    public function testItCanFindAPasswordHashWithAUsername(): void
    {
        $account = AccountProvider::activeAccount();

        self::loadFixtures($account);

        $repository = new AccountReaderRepository(self::entityManager());

        self::assertInstanceOf(
            AccountDetailsForAuthentication::class,
            $repository->readModelForAuthentication($account->username()->value())
        );
    }

    /**
     * @throws Exception
     */
    public function testItCanFindAPasswordHashWithAnEmail(): void
    {
        $account = AccountProvider::activeAccount();

        self::loadFixtures($account);

        $repository = new AccountReaderRepository(self::entityManager());

        self::assertInstanceOf(
            AccountDetailsForAuthentication::class,
            $repository->readModelForAuthentication($account->email()->value())
        );
    }

    /**
     * @throws Exception
     */
    public function testItReturnsNullIfEmailDoesNotExist(): void
    {
        $repository = new AccountReaderRepository(self::entityManager());

        self::assertNull($repository->readModelForAuthentication('does not exist'));
    }
}
