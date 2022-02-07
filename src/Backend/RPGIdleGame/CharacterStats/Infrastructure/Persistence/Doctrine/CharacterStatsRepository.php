<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\CharacterStats\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\CharacterStats;
use Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\CharacterStatsGateway;
use Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\ValueObject\CharacterStatsCharacterId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class CharacterStatsRepository extends DoctrineRepository implements CharacterStatsGateway
{
    /**
     * @throws Exception
     */
    public function save(CharacterStats $characterStats): void
    {
        $query = 'UPDATE characters SET fights_count = :fights, wins_count = :wins, draws_count = :draws, losses_count = :losses WHERE id = :id;';

        $parameters = [
            'id'     => $characterStats->characterId()->value(),
            'fights' => $characterStats->fightsCount()->value(),
            'wins'   => $characterStats->winsCount()->value(),
            'draws'  => $characterStats->drawsCount()->value(),
            'losses' => $characterStats->lossesCount()->value(),
        ];

        $this->entityManager->getConnection()->executeStatement($query, $parameters);
    }

    /**
     * @throws Exception
     */
    public function findOneById(CharacterStatsCharacterId $characterId): ?CharacterStats
    {
        /** @var array{wins_count: int, draws_count: int, losses_count: int}|false $data */
        $data = $this->entityManager->getConnection()->fetchAssociative(
            'SELECT wins_count, draws_count, losses_count FROM characters WHERE id = :id',
            ['id' => $characterId],
        );

        if (false === $data) {
            return null;
        }

        return CharacterStats::fromScalars(
            characterId: $characterId,
            wins: $data['wins_count'],
            draws: $data['draws_count'],
            losses: $data['losses_count'],
        );
    }
}
