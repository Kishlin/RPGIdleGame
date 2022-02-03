<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\RPGIdleGame\Character\Application\CreateCharacter\CreationAllowanceGateway;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class CreationAllowanceRepository extends DoctrineRepository implements CreationAllowanceGateway
{
    /**
     * @throws Exception
     */
    public function isAllowedToCreateACharacter(CharacterOwner $characterOwner): bool
    {
        $characterCountReachedLimit = $this->entityManager->getConnection()->fetchOne(
            'SELECT reached_limit FROM character_counts WHERE owner_id = :owner LIMIT 1;',
            ['owner' => $characterOwner->value()],
        );

        return false === $characterCountReachedLimit;
    }
}
