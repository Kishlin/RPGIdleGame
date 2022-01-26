<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\RPGIdleGame\Character\Application\DeleteCharacter\DeletionAllowanceGateway;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class DeletionAllowanceRepository extends DoctrineRepository implements DeletionAllowanceGateway
{
    /**
     * @throws Exception
     */
    public function requesterIsTheRightfulOwner(CharacterOwner $deletionRequester, CharacterId $characterId): bool
    {
        $requesterOwnsTheCharacter = $this->entityManager->getConnection()->fetchOne(
            'SELECT 1 FROM characters WHERE character_owner = :requester AND character_id = :characterId LIMIT 1;',
            ['requester' => $deletionRequester->value(), 'characterId' => $characterId->value()],
        );

        return false !== $requesterOwnsTheCharacter;
    }
}
