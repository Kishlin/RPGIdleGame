<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\Account\Domain\Account;
use Kishlin\Backend\Account\Domain\AccountEmail;
use Kishlin\Backend\Account\Domain\AccountGateway;
use Kishlin\Backend\Account\Domain\AccountId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class AccountRepository extends DoctrineRepository implements AccountGateway
{
    public function save(Account $account): void
    {
        $this->entityManager->persist($account);
        $this->entityManager->flush();
    }

    public function findOneById(AccountId $accountId): ?Account
    {
        return $this->entityManager->getRepository(Account::class)->findOneBy(['id' => $accountId]);
    }

    /**
     * @throws Exception
     */
    public function thereAlreadyIsAnAccountWithEmail(AccountEmail $accountEmail): bool
    {
        $foundAnAccountWithEmail = $this->entityManager->getConnection()->fetchOne(
            'SELECT 1 FROM accounts WHERE account_email = :email LIMIT 1;',
            ['email' => $accountEmail->value()],
        );

        return false !== $foundAnAccountWithEmail;
    }
}
