<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\Context;

use Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\HTTPClient\Request;
use PHPUnit\Framework\Assert;

final class FightAccessContext extends RPGIdleGameAPIContext
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
INSERT INTO fights (id, initiator, opponent, winner_id)
VALUES (:fight, '6655cc1c-d2f8-4e8e-a2c9-f22712f98ae0', 'e146ed07-5c7c-4722-afc1-c63c8c94c141', :fighter)
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
     * @When /^a client asks to view one the fight's infos$/
     */
    public function aClientAsksToViewOneTheFightsInfos(): void
    {
        $this->response = null;
        $this->response = self::client()->get(new Request(
            uri: '/fight/' . self::FIGHT_UUID,
            headers: [
                'Authorization: Bearer ' . self::AUTHENTICATION_FOR_CLIENT,
            ],
        ));
    }

    /**
     * @When /^a client asks to view a fight that does not exist$/
     */
    public function aClientAsksToViewAFightThatDoesNotExist(): void
    {
        $this->response = null;
        $this->response = self::client()->get(new Request(
            uri: '/fight/fight-that-does-not-exist',
            headers: [
                'Authorization: Bearer ' . self::AUTHENTICATION_FOR_CLIENT,
            ],
        ));
    }

    /**
     * @When /^a stranger tries to view the fight's infos$/
     */
    public function aStrangerTriesToViewTheFightsInfos(): void
    {
        $this->response = null;
        $this->response = self::client()->get(new Request(
            uri: '/fight/' . self::FIGHT_UUID,
            headers: [
                'Authorization: Bearer ' . self::AUTHENTICATION_FOR_STRANGER,
            ],
        ));
    }

    /**
     * @Then /^details about the fight were returned$/
     */
    public function detailsAboutTheFightWereReturned(): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertSame(200, $this->response->httpCode());

        $content = $this->response->decodedBody();

        Assert::assertIsArray($content);
        Assert::assertArrayHasKey('id', $content);
        Assert::assertSame(self::FIGHT_UUID, $content['id']);
        Assert::assertCount(5, $content['turns']);
    }

    /**
     * @Then /^the query for the fight infos was refused$/
     */
    public function theQueryForTheFightInfosWasRefused(): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertSame(404, $this->response->httpCode());
    }
}
