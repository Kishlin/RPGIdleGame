<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\RPGIdleGame\Character\Application\DistributeSkillPoints\CharacterNotFoundException;
use Kishlin\Backend\RPGIdleGame\Character\Domain\CharacterViewGateway;
use Kishlin\Backend\RPGIdleGame\Character\Domain\View\CompleteCharacterView;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineViewer;

class CharacterViewRepository extends DoctrineViewer implements CharacterViewGateway
{
    /**
     * @throws CharacterNotFoundException|Exception
     */
    public function viewOneById(string $characterId, string $requesterId): CompleteCharacterView
    {
        /** @var array<string, int|string>|false $data */
        $data = $this->entityManager->getConnection()->fetchAssociative(
            'SELECT * from characters WHERE character_id = :id AND character_owner = :owner',
            ['id' => $characterId, 'owner' => $requesterId],
        );

        if (false === $data) {
            throw new CharacterNotFoundException();
        }

        return CompleteCharacterView::fromSource($data);
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function viewAllForOwner(string $ownerUuid): array
    {
        /** @var array<int, array<string, int|string>>|false $data */
        $data = $this->entityManager->getConnection()->fetchAllAssociative(
            'SELECT * from characters WHERE character_owner = :owner',
            ['owner' => $ownerUuid],
        );

        if (false === $data) {
            throw new CharacterNotFoundException();
        }

        return array_map([CompleteCharacterView::class, 'fromSource'], $data);
    }
}
