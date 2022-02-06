<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\Account\Domain\AccountReaderGateway;
use Kishlin\Backend\Account\Domain\ReadModel\AccountDetailsForAuthentication;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class AccountReaderRepository extends DoctrineRepository implements AccountReaderGateway
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
}
