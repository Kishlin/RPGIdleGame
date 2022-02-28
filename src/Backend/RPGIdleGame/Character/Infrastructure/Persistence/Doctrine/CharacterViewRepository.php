<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\RPGIdleGame\Character\Application\DistributeSkillPoints\CharacterNotFoundException;
use Kishlin\Backend\RPGIdleGame\Character\Domain\CharacterViewGateway;
use Kishlin\Backend\RPGIdleGame\Character\Domain\View\JsonableCharactersListView;
use Kishlin\Backend\RPGIdleGame\Character\Domain\View\JsonableCharacterView;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineViewer;

class CharacterViewRepository extends DoctrineViewer implements CharacterViewGateway
{
    private const SELECT_ONE_FOR_OWNER = <<<'SQL'
SELECT id, name, owner, skill_points, health, attack, defense, magik, rank, fights_count, wins_count, draws_count, losses_count, cast(extract(epoch from created_on) as integer) as created_on, cast(extract(epoch from available_as_of) as integer) as available_as_of
FROM characters
WHERE id = :id AND owner = :owner AND is_active IS TRUE
LIMIT 1
SQL;

    private const SELECT_ALL_FOR_OWNER = <<<'SQL'
SELECT id, name, owner, skill_points, health, attack, defense, magik, rank, fights_count, wins_count, draws_count, losses_count, cast(extract(epoch from created_on) as integer) as created_on, cast(extract(epoch from available_as_of) as integer) as available_as_of
FROM characters
WHERE owner = :owner AND is_active IS TRUE
ORDER BY created_on ASC
SQL;

    /**
     * @throws CharacterNotFoundException|Exception
     */
    public function viewOneById(string $characterId, string $requesterId): JsonableCharacterView
    {
        /**
         * @var array{id: string, name: string, owner: string, skill_points: int, health: int, attack: int, defense: int, magik: int, rank: int, fights_count: int, wins_count: int, draws_count: int, losses_count: int, created_on: int, available_as_of: int}|false $data
         */
        $data = $this->entityManager->getConnection()->fetchAssociative(
            self::SELECT_ONE_FOR_OWNER,
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
    public function viewAllForOwner(string $ownerUuid): JsonableCharactersListView
    {
        /**
         * @var array<array{id: string, name: string, owner: string, skill_points: int, health: int, attack: int, defense: int, magik: int, rank: int, fights_count: int, wins_count: int, draws_count: int, losses_count: int, created_on: int, available_as_of: int}>|false $data
         */
        $data = $this->entityManager->getConnection()->fetchAllAssociative(
            self::SELECT_ALL_FOR_OWNER,
            ['owner' => $ownerUuid],
        );

        if (false === $data) {
            throw new CharacterNotFoundException();
        }

        return JsonableCharactersListView::fromSource($data);
    }
}
