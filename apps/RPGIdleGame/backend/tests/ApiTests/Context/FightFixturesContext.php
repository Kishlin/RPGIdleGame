<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\Context;

final class FightFixturesContext extends RPGIdleGameAPIContext
{
    /**
     * @Given /^its character took part in a fight with the opponent$/
     */
    public function itsCharacterTookPartInAFightWithTheOpponent(): void
    {
        $initiatorQuery = <<<'SQL'
INSERT INTO fight_initiators (id, character_id, health, attack, defense, magik, rank)
VALUES ('6655cc1c-d2f8-4e8e-a2c9-f22712f98ae0', :initiator, 80, 56, 23, 34, 125)
SQL;

        $opponentQuery = <<<'SQL'
INSERT INTO fight_opponents (id, character_id, health, attack, defense, magik, rank)
VALUES ('e146ed07-5c7c-4722-afc1-c63c8c94c141', :opponent, 70, 48, 28, 30, 120)
SQL;

        $fightQuery = <<<'SQL'
INSERT INTO fights (id, initiator, opponent, winner_id, fight_date)
VALUES (:fight, '6655cc1c-d2f8-4e8e-a2c9-f22712f98ae0', 'e146ed07-5c7c-4722-afc1-c63c8c94c141', :fighter, '1993-11-22 15:00:00')
SQL;

        $fightTurnsQuery = <<<'SQL'
INSERT INTO fight_turns (id, fight_id, attacker_id, index, attacker_attack, attacker_magik, attacker_dice_roll, defender_defense, damage_dealt, defender_health)
VALUES ('a5928808-2a59-4f07-9649-9a34ba28a101', :fight, '6655cc1c-d2f8-4e8e-a2c9-f22712f98ae0', 0, 56, 34, 34, 28, 40, 30),
('fade0880-06d1-42c2-be6d-f1118c0c6f42', :fight, 'e146ed07-5c7c-4722-afc1-c63c8c94c141', 1, 48, 30, 46, 23, 23, 57),
('2957c1b2-a295-40ce-a8cd-ae21eae89bf1', :fight, '6655cc1c-d2f8-4e8e-a2c9-f22712f98ae0', 2, 56, 34, 55, 28, 27, 3),
('69edbaee-094a-4ba1-b3a4-6b28392d8b49', :fight, 'e146ed07-5c7c-4722-afc1-c63c8c94c141', 3, 48, 30, 15, 23, 0, 57),
('7a2d22b7-f1d5-493f-bb41-5e618e04ffe9', :fight, '6655cc1c-d2f8-4e8e-a2c9-f22712f98ae0', 4, 56, 34, 42, 28, 14, 0)
SQL;

        self::database()->exec($initiatorQuery, ['initiator' => self::FIGHTER_UUID]);

        self::database()->exec($opponentQuery, ['opponent' => self::OPPONENT_UUID]);

        self::database()->exec($fightQuery, ['fight' => self::FIGHT_UUID, 'fighter' => self::FIGHTER_UUID]);

        self::database()->exec($fightTurnsQuery, ['fight' => self::FIGHT_UUID]);
    }

    /**
     * @Given /^its character took part in a few fights$/
     */
    public function itsCharacterTookPartInAFewFights(): void
    {
        $initiatorQuery = <<<'SQL'
INSERT INTO fight_initiators (id, character_id, health, attack, defense, magik, rank)
VALUES ('6655cc1c-d2f8-4e8e-a2c9-f22712f98ae0', :initiator, 80, 56, 23, 34, 125)
SQL;

        $opponentQuery = <<<'SQL'
INSERT INTO fight_opponents (id, character_id, health, attack, defense, magik, rank)
VALUES ('e146ed07-5c7c-4722-afc1-c63c8c94c141', :opponent, 70, 48, 28, 30, 120)
SQL;

        $fightQuery = <<<'SQL'
INSERT INTO fights (id, initiator, opponent, winner_id, fight_date)
VALUES ('5771b56d-4e62-4989-802a-376760634b7e', '6655cc1c-d2f8-4e8e-a2c9-f22712f98ae0', 'e146ed07-5c7c-4722-afc1-c63c8c94c141', :fighter, '1993-11-22 16:00:00'),
('b5afa950-f164-4b21-81ac-9d510492b0d1', '6655cc1c-d2f8-4e8e-a2c9-f22712f98ae0', 'e146ed07-5c7c-4722-afc1-c63c8c94c141', :opponent, '1993-11-22 17:00:00'),
('885ad1bc-9eae-405e-86e0-5252bccd1e3a', '6655cc1c-d2f8-4e8e-a2c9-f22712f98ae0', 'e146ed07-5c7c-4722-afc1-c63c8c94c141', null, '1993-11-22 18:00:00')
SQL;
        self::database()->exec($initiatorQuery, ['initiator' => self::FIGHTER_UUID]);

        self::database()->exec($opponentQuery, ['opponent' => self::OPPONENT_UUID]);

        self::database()->exec($fightQuery, ['fighter' => self::FIGHTER_UUID, 'opponent' => self::OPPONENT_UUID]);
    }

    /**
     * @Given /^its character did not take part in any fights$/
     */
    public function itsCharacterDidNotTakePartInAnyFights(): void
    {
    }
}
