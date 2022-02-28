<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\Context;

final class CharacterFixturesContext extends RPGIdleGameAPIContext
{
    /**
     * @Given it has reached the character limit
     */
    public function itHasReachedTheCharacterLimit(): void
    {
        self::database()->exec(
            'UPDATE character_counts SET reached_limit = true WHERE owner_id = :owner;',
            ['owner' => self::CLIENT_UUID],
        );
    }

    /**
     * @Given /^it owns a character$/
     */
    public function itOwnsACharacter(): void
    {
        $query = <<<'SQL'
INSERT INTO characters (id, owner, name, skill_points, health, attack, defense, magik, rank, is_active)
VALUES (:id, :owner, 'Kishlin', 12, 10, 0, 0, 0, 1, true);
SQL;

        self::database()->exec($query, ['id' => self::FIGHTER_UUID, 'owner' => self::CLIENT_UUID]);

        self::database()->exec(
            'UPDATE character_counts SET character_count = character_count + 1 WHERE owner_id = :owner;',
            ['owner' => self::CLIENT_UUID],
        );
    }

    /**
     * @Given /^it owns a well advanced character$/
     */
    public function itOwnsAWellAdvancedCharacter(): void
    {
        $query = <<<'SQL'
INSERT INTO characters (id, owner, name, skill_points, health, attack, defense, magik, rank, is_active)
VALUES (:id, :owner, 'Kishlin', 3000, 80, 56, 23, 34, 125, true);
SQL;

        self::database()->exec($query, ['id' => self::FIGHTER_UUID, 'owner' => self::CLIENT_UUID]);

        self::database()->exec(
            'UPDATE character_counts SET character_count = character_count + 1 WHERE owner_id = :owner;',
            ['owner' => self::CLIENT_UUID],
        );
    }

    /**
     * @Given /^it owns a few characters$/
     */
    public function itOwnsAFewCharacters(): void
    {
        $queries = [
            <<<'SQL'
INSERT INTO characters (id, owner, name, skill_points, health, attack, defense, magik, rank, is_active, fights_count, wins_count, draws_count, losses_count)
VALUES ('88c7ee64-27e5-409c-b8a2-0db1aba2131b', :owner, 'Fighter', 12, 10, 0, 0, 0, 1, true, 0, 0, 0, 0);
SQL,
            <<<'SQL'
INSERT INTO characters (id, owner, name, skill_points, health, attack, defense, magik, rank, is_active, fights_count, wins_count, draws_count, losses_count)
VALUES ('0c023953-0bfd-43ac-81f9-e608cef3e3f6', :owner, 'Brawler', 24, 56, 28, 13, 26, 125, true, 68, 30, 5, 33);
SQL,
            <<<'SQL'
INSERT INTO characters (id, owner, name, skill_points, health, attack, defense, magik, rank, is_active, fights_count, wins_count, draws_count, losses_count)
VALUES ('5a4440cf-4ebf-4f6a-a356-fee321f719ed', :owner, 'Magician', 13, 128, 64, 52, 35, 226, true, 1688, 824, 113, 751);
SQL,
        ];

        foreach ($queries as $query) {
            self::database()->exec($query, ['owner' => self::CLIENT_UUID]);
        }

        self::database()->exec(
            'UPDATE character_counts SET character_count = character_count + 3 WHERE owner_id = :owner;',
            ['owner' => self::CLIENT_UUID],
        );
    }
}
