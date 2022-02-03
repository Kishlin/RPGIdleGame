<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\CannotAccessFightsException;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightNotFoundException;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightViewGateway;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\View\SerializableFightListItem;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\View\SerializableFightView;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class FightViewRepository extends DoctrineRepository implements FightViewGateway
{
    private const ALL_FIGHTS_FOR_FIGHTER_QUERY = <<<'SQL'
SELECT fights.id, fights.winner_id, initiators.character_name as initiator_name, fight_initiators.rank as initiator_rank, opponents.character_name as opponent_name, fight_opponents.rank as opponent_rank
FROM fights
LEFT JOIN fight_initiators ON fight_initiators.id = fights.initiator
LEFT JOIN fight_opponents ON fight_opponents.id = fights.opponent
LEFT JOIN characters opponents ON opponents.character_id = fight_opponents.character_id
LEFT JOIN characters initiators ON initiators.character_id = fight_initiators.character_id
WHERE (
    opponents.character_owner = :requester_id AND opponents.character_id = :fighter_id
) OR (
    initiators.character_owner = :requester_id AND initiators.character_id = :fighter_id
)
;
SQL;

    private const FIGHT_QUERY = <<<'SQL'
SELECT fights.id, fights.winner_id
FROM characters
LEFT JOIN fight_initiators ON fight_initiators.character_id = characters.character_id
LEFT JOIN fight_opponents ON fight_opponents.character_id = characters.character_id
LEFT JOIN fights ON fights.initiator = fight_initiators.id OR fights.opponent = fight_opponents.id
WHERE characters.character_owner = :requester_id
AND fights.id = :fight_id
LIMIT 1
;
SQL;

    private const INITIATOR_QUERY = <<<'SQL'
SELECT accounts.account_username, characters.character_name, fight_initiators.health, fight_initiators.attack, fight_initiators.defense, fight_initiators.magik , fight_initiators.rank
FROM fights
LEFT JOIN fight_initiators ON fight_initiators.id = fights.initiator
LEFT JOIN characters ON characters.character_id = fight_initiators.character_id
LEFT JOIN accounts ON characters.character_owner = accounts.account_id
WHERE fights.id = :fight_id
LIMIT 1
;
SQL;

    private const OPPONENT_QUERY = <<<'SQL'
SELECT accounts.account_username, characters.character_name, fight_opponents.health, fight_opponents.attack, fight_opponents.defense, fight_opponents.magik , fight_opponents.rank
FROM fights
LEFT JOIN fight_opponents ON fight_opponents.id = fights.opponent
LEFT JOIN characters ON characters.character_id = fight_opponents.character_id
LEFT JOIN accounts ON characters.character_owner = accounts.account_id
WHERE fights.id = :fight_id
LIMIT 1
;
SQL;

    private const TURNS_QUERY = <<<'SQL'
SELECT CASE
    WHEN index % 2 = 0
        THEN (
            SELECT character_name
            FROM characters
            LEFT JOIN fight_initiators ON fight_initiators.character_id = characters.character_id
            WHERE fight_initiators.id = fight_turns.attacker_id
        )
        ELSE (
            SELECT character_name
            FROM characters
            LEFT JOIN fight_opponents ON fight_opponents.character_id = characters.character_id
            WHERE fight_opponents.id = fight_turns.attacker_id
        )
    END
as character_name,  index, attacker_attack, attacker_magik, attacker_dice_roll, defender_defense, damage_dealt, defender_health
FROM fight_turns
WHERE fight_id = 'fight-2'
ORDER BY index ASC
;
SQL;

    /**
     * @throws Exception|FightNotFoundException
     */
    public function viewOneById(string $fightId, string $requesterId): SerializableFightView
    {
        $connection = $this->entityManager->getConnection();

        /**
         * @var array{id: string, winner_id: ?string}|false $fight
         */
        $fight = $connection->fetchAssociative(
            self::FIGHT_QUERY,
            ['fight_id' => $fightId, 'requester_id' => $requesterId],
        );

        if (false === $fight) {
            throw new FightNotFoundException();
        }

        $fight['initiator'] = $this->initiator($fightId);

        $fight['opponent'] = $this->opponent($fightId);

        $fight['turns'] = $this->getTurns($fightId);

        return SerializableFightView::fromSource($fight);
    }

    /**
     * {@inheritDoc}
     *
     * @throws CannotAccessFightsException|Exception
     */
    public function viewAllForFighter(string $fighterId, string $requesterId): array
    {
        /**
         * @var array<array{id: string, winner_id: ?string, initiator_name: string, initiator_rank: int, opponent_name: string, opponent_rank: int}>|false $fights
         */
        $fights = $this->entityManager->getConnection()->fetchAllAssociative(
            self::ALL_FIGHTS_FOR_FIGHTER_QUERY,
            ['fighter_id' => $fighterId, 'requester_id' => $requesterId]
        );

        if (false === $fights) {
            throw new CannotAccessFightsException();
        }

        return array_map(static fn ($source) => SerializableFightListItem::fromSource($source), $fights);
    }

    /**
     * @throws Exception
     *
     * @return array{account_username: string, character_name: string, health: int, attack: int, defense: int, magik: int, rank: int}
     */
    private function initiator(string $fightId): array
    {
        /** @var array{account_username: string, character_name: string, health: int, attack: int, defense: int, magik: int, rank: int}|false $ret */
        $ret = $this->entityManager->getConnection()->fetchAssociative(self::INITIATOR_QUERY, ['fight_id' => $fightId]);
        assert(false !== $ret);

        return $ret;
    }

    /**
     * @throws Exception
     *
     * @return array{account_username: string, character_name: string, health: int, attack: int, defense: int, magik: int, rank: int}
     */
    private function opponent(string $fightId): array
    {
        /** @var array{account_username: string, character_name: string, health: int, attack: int, defense: int, magik: int, rank: int}|false $ret */
        $ret = $this->entityManager->getConnection()->fetchAssociative(self::OPPONENT_QUERY, ['fight_id' => $fightId]);
        assert(false !== $ret);

        return $ret;
    }

    /**
     * @throws Exception
     *
     * @return array<array{character_name: string, index: int, attacker_attack: int, attacker_magik: int, attacker_dice_roll: int, defender_defense: int, damage_dealt: int, defender_health: int}>
     */
    private function getTurns(string $fightId): array
    {
        /** @var array<array{character_name: string, index: int, attacker_attack: int, attacker_magik: int, attacker_dice_roll: int, defender_defense: int, damage_dealt: int, defender_health: int}> $ret */
        $ret = $this->entityManager->getConnection()->fetchAllAssociative(self::TURNS_QUERY, ['fight_id' => $fightId]);

        assert(true); // Makes php-cs-fixer and phpstan happy together.

        return $ret;
    }
}
