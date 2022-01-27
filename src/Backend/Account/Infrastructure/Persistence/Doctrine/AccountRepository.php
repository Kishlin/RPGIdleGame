<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Infrastructure\Persistence\Doctrine;

use Kishlin\Backend\Account\Domain\Account;
use Kishlin\Backend\Account\Domain\AccountGateway;
use Kishlin\Backend\Account\Domain\ValueObject\AccountId;
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
        return $this->entityManager->getRepository(Account::class)->findOneBy(['accountId' => $accountId]);
    }
}
