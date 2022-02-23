<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\Account\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\Account\Domain\ReadModel\AccountDetailsForAuthentication;
use Kishlin\Backend\Account\Domain\ValueObject\AccountUsername;
use Kishlin\Backend\Account\Infrastructure\Persistence\Doctrine\AccountReaderRepository;
use Kishlin\Tests\Backend\Tools\Provider\AccountProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Account\Infrastructure\Persistence\Doctrine\AccountReaderRepository
 */
final class AccountReaderRepositoryTest extends RepositoryContractTestCase
{
    /**
     * @throws Exception
     */
    public function testItCanFindAPasswordHashWithAUsername(): void
    {
        $account    = AccountProvider::activeAccount();
        $repository = new AccountReaderRepository(self::entityManager());

        self::loadFixtures($account);

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
        $account    = AccountProvider::activeAccount();
        $repository = new AccountReaderRepository(self::entityManager());

        self::loadFixtures($account);

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

    /**
     * @throws Exception
     */
    public function testItChecksTheExistenceOfAUserWithSalt(): void
    {
        $account    = AccountProvider::activeAccount();
        $repository = new AccountReaderRepository(self::entityManager());

        self::loadFixtures($account);

        self::assertTrue(
            $repository->theUserExistsWithThisSalt($account->id()->value(), $account->salt()->value()),
        );

        self::assertFalse($repository->theUserExistsWithThisSalt('invalid-user-id', $account->salt()->value()));

        self::assertFalse($repository->theUserExistsWithThisSalt($account->id()->value(), 'invalid-salt'));
    }

    /**
     * @throws Exception
     */
    public function testItCanDetectTheEmailIsAlreadyUsed(): void
    {
        $repository   = new AccountReaderRepository(self::entityManager());
        $account      = AccountProvider::activeAccount();
        $accountEmail = $account->email();

        self::assertFalse($repository->thereAlreadyIsAnAccountWithEmail($accountEmail));

        $this->loadFixtures($account);

        self::assertTrue($repository->thereAlreadyIsAnAccountWithEmail($accountEmail));
    }

    /**
     * @throws Exception
     */
    public function testItCanDetectTheUsernameIsAlreadyUsed(): void
    {
        $repository      = new AccountReaderRepository(self::entityManager());
        $account         = AccountProvider::activeAccount();
        $accountUsername = $account->username();

        self::assertFalse($repository->thereAlreadyIsAnAccountWithUsername($accountUsername));

        $this->loadFixtures($account);

        self::assertTrue($repository->thereAlreadyIsAnAccountWithUsername($accountUsername));

        $uppercaseUsername = new AccountUsername(strtoupper($accountUsername->value()));

        self::assertTrue($repository->thereAlreadyIsAnAccountWithUsername($uppercaseUsername)); // case-insensitive
    }
}
