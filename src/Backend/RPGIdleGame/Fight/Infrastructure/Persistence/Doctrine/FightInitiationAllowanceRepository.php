<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\RPGIdleGame\Fight\Application\InitiateAFight\FighterId;
use Kishlin\Backend\RPGIdleGame\Fight\Application\InitiateAFight\FightInitiationAllowanceGateway;
use Kishlin\Backend\RPGIdleGame\Fight\Application\InitiateAFight\FightRequesterId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class FightInitiationAllowanceRepository extends DoctrineRepository implements FightInitiationAllowanceGateway
{
    /**
     * @throws Exception
     */
    public function requesterIsAllowedToFightWithFighter(FightRequesterId $requesterId, FighterId $fighterId): bool
    {
        $requesterOwnsTheFighter = $this->entityManager->getConnection()->fetchOne(
            'SELECT 1 FROM characters WHERE character_owner = :requester AND character_id = :characterId LIMIT 1;',
            ['requester' => $requesterId->value(), 'characterId' => $fighterId->value()],
        );

        return false !== $requesterOwnsTheFighter;
    }
}
