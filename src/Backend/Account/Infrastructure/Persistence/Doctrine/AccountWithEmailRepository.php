<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\Account\Application\Signup\AccountWithEmailGateway;
use Kishlin\Backend\Account\Domain\ValueObject\AccountEmail;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class AccountWithEmailRepository extends DoctrineRepository implements AccountWithEmailGateway
{
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
