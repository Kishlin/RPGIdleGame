<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\Account\Application\Signup\AccountWithEmailGateway;
use Kishlin\Backend\Account\Application\Signup\AccountWithUsernameGateway;
use Kishlin\Backend\Account\Domain\AccountReaderGateway;
use Kishlin\Backend\Account\Domain\ReadModel\AccountDetailsForAuthentication;
use Kishlin\Backend\Account\Domain\ValueObject\AccountEmail;
use Kishlin\Backend\Account\Domain\ValueObject\AccountUsername;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class AccountReaderRepository extends DoctrineRepository implements AccountReaderGateway, AccountWithEmailGateway, AccountWithUsernameGateway
{
    /**
     * @throws Exception
     */
    public function readModelForAuthentication(string $usernameOrEmail): ?AccountDetailsForAuthentication
    {
        /** @var array{id: string, password: string, salt: string}|false $data */
        $data = $this->entityManager->getConnection()->fetchAssociative(
            'SELECT id, password, salt FROM accounts WHERE email = :authenticationKey OR username = :authenticationKey LIMIT 1;',
            ['authenticationKey' => $usernameOrEmail],
        );

        return $data ? AccountDetailsForAuthentication::fromScalars($data['id'], $data['password'], $data['salt']) : null;
    }

    /**
     * @throws Exception
     */
    public function theUserExistsWithThisSalt(string $userId, string $salt): bool
    {
        /** @var false|int $data */
        $data = $this->entityManager->getConnection()->fetchOne(
            'SELECT 1 FROM accounts WHERE id = :userId AND salt = :salt LIMIT 1;',
            ['userId' => $userId, 'salt' => $salt],
        );

        return false !== $data;
    }

    /**
     * @throws Exception
     */
    public function thereAlreadyIsAnAccountWithEmail(AccountEmail $accountEmail): bool
    {
        $foundAnAccountWithEmail = $this->entityManager->getConnection()->fetchOne(
            'SELECT 1 FROM accounts WHERE email = :email LIMIT 1;',
            ['email' => $accountEmail->value()],
        );

        return false !== $foundAnAccountWithEmail;
    }

    /**
     * @throws Exception
     */
    public function thereAlreadyIsAnAccountWithUsername(AccountUsername $accountUsername): bool
    {
        $foundAnAccountWithUsername = $this->entityManager->getConnection()->fetchOne(
            'SELECT 1 FROM accounts WHERE LOWER(username) = LOWER(:username) LIMIT 1;',
            ['username' => $accountUsername->value()],
        );

        return false !== $foundAnAccountWithUsername;
    }
}
