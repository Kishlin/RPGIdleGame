<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\RPGIdleGame\Character\Application\DistributeSkillPoints\CharacterNotFoundException;
use Kishlin\Backend\RPGIdleGame\Character\Domain\CharacterViewGateway;
use Kishlin\Backend\RPGIdleGame\Character\Domain\View\JsonableCharacterView;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineViewer;

class CharacterViewRepository extends DoctrineViewer implements CharacterViewGateway
{
    /**
     * @throws CharacterNotFoundException|Exception
     */
    public function viewOneById(string $characterId, string $requesterId): JsonableCharacterView
    {
        /**
         * @var array{id: string, name: string, owner: string, skill_points: int, health: int, attack: int, defense: int, magik: int, rank: int, fights_count: int, wins_count: int, draws_count: int, losses_count: int}|false $data
         */
        $data = $this->entityManager->getConnection()->fetchAssociative(
            'SELECT * from characters WHERE id = :id AND owner = :owner',
            ['id' => $characterId, 'owner' => $requesterId],
        );

        if (false === $data) {
            throw new CharacterNotFoundException();
        }

        return JsonableCharacterView::fromSource($data);
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function viewAllForOwner(string $ownerUuid): array
    {
        /**
         * @var array<array{id: string, name: string, owner: string, skill_points: int, health: int, attack: int, defense: int, magik: int, rank: int, fights_count: int, wins_count: int, draws_count: int, losses_count: int}>|false $data
         */
        $data = $this->entityManager->getConnection()->fetchAllAssociative(
            'SELECT * from characters WHERE owner = :owner',
            ['owner' => $ownerUuid],
        );

        if (false === $data) {
            throw new CharacterNotFoundException();
        }

        return array_map([JsonableCharacterView::class, 'fromSource'], $data);
    }
}
