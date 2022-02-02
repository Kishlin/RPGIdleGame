<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\RPGIdleGame\Character\Application\DistributeSkillPoints\CharacterNotFoundException;
use Kishlin\Backend\RPGIdleGame\Character\Domain\CharacterViewGateway;
use Kishlin\Backend\RPGIdleGame\Character\Domain\View\SerializableCharacterView;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineViewer;

class CharacterViewRepository extends DoctrineViewer implements CharacterViewGateway
{
    /**
     * @throws CharacterNotFoundException|Exception
     */
    public function viewOneById(string $characterId, string $requesterId): SerializableCharacterView
    {
        /**
         * @var false|array{
         *     character_id:           string,
         *     character_name:         string,
         *     character_owner:        string,
         *     character_skill_points: int,
         *     character_health:       int,
         *     character_attack:       int,
         *     character_defense:      int,
         *     character_magik:        int,
         *     character_rank:         int,
         *     character_fights_count: int,
         * } $data
         */
        $data = $this->entityManager->getConnection()->fetchAssociative(
            'SELECT * from characters WHERE character_id = :id AND character_owner = :owner',
            ['id' => $characterId, 'owner' => $requesterId],
        );

        if (false === $data) {
            throw new CharacterNotFoundException();
        }

        return SerializableCharacterView::fromSource($data);
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function viewAllForOwner(string $ownerUuid): array
    {
        /**
         * @var false|array{
         *     character_id:           string,
         *     character_name:         string,
         *     character_owner:        string,
         *     character_skill_points: int,
         *     character_health:       int,
         *     character_attack:       int,
         *     character_defense:      int,
         *     character_magik:        int,
         *     character_rank:         int,
         *     character_fights_count: int,
         * }[] $data
         */
        $data = $this->entityManager->getConnection()->fetchAllAssociative(
            'SELECT * from characters WHERE character_owner = :owner',
            ['owner' => $ownerUuid],
        );

        if (false === $data) {
            throw new CharacterNotFoundException();
        }

        return array_map([SerializableCharacterView::class, 'fromSource'], $data);
    }
}
