<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\Context;

use Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\HTTPClient\Request;
use PHPUnit\Framework\Assert;

final class FightContext extends RPGIdleGameAPIContext
{
    /**
     * @Given /^there is an opponent available$/
     */
    public function thereIsAnOpponentAvailable(): void
    {
        $query = <<<'SQL'
INSERT INTO characters (id, owner, name, skill_points, health, attack, defense, magik, rank)
VALUES (:id, :stranger, 'Opponent', 0, 70, 48, 28, 30, 120);
SQL;

        self::database()->exec($query, ['id' => self::OPPONENT_UUID, 'stranger' => self::STRANGER_UUID]);
    }

    /**
     * @Given /^there is no available opponent$/
     */
    public function thereIsNoAvailableOpponent(): void
    {
    }

    /**
     * @When /^a client wants to fight with its character$/
     */
    public function aClientWantsToFightWithItsCharacter(): void
    {
        $this->response = null;
        $this->response = self::client()->post(new Request(
            uri: '/fight/initiate/' . self::FIGHTER_UUID,
            headers: [
                'Authorization: Bearer ' . self::AUTHENTICATION_FOR_CLIENT,
            ],
        ));
    }

    /**
     * @When /^a stranger tries to fight with the client's character$/
     */
    public function aStrangerTriesToFightWithTheClientsCharacter(): void
    {
        $this->response = null;
        $this->response = self::client()->post(new Request(
            uri: '/fight/initiate/' . self::FIGHTER_UUID,
            headers: [
                'Authorization: Bearer ' . self::AUTHENTICATION_FOR_STRANGER,
            ],
        ));
    }

    /**
     * @Then /^the fight is registered$/
     */
    public function theFightIsRegistered(): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertSame(201, $this->response->httpCode());

        $data = $this->response->decodedBody();

        Assert::assertIsArray($data);
        Assert::assertArrayHasKey('fightId', $data);

        Assert::assertSame(
            1,
            self::database()->fetchOne('SELECT count(1) FROM fights WHERE id = :id', ['id' => $data['fightId']]),
        );
    }

    /**
     * @Then /^the fighting stats of both participants where updated$/
     */
    public function theFightingStatsOfBothParticipantsWhereUpdated(): void
    {
        /** @var array<array{fights_count: int, wins_count: int, draws_count: int, losses_count: int}> $fightersStats */
        $fightersStats = self::database()->fetchAllAssociative(
            'SELECT fights_count, wins_count, draws_count, losses_count FROM characters',
        );

        Assert::assertCount(2, $fightersStats);

        foreach ($fightersStats as $fighterStats) {
            Assert::assertSame(1, $fighterStats['wins_count'] + $fighterStats['losses_count']);
            Assert::assertSame(1, $fighterStats['fights_count']);
            Assert::assertSame(0, $fighterStats['draws_count']);
        }
    }

    /**
     * @Then /^the fight request was refused$/
     */
    public function theFightRequestWasRefused(): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertSame(403, $this->response->httpCode());
        Assert::assertSame(0, self::database()->fetchOne('SELECT count(1) FROM fights'));
    }

    /**
     * @Then /^the fight request failed to find an opponent$/
     */
    public function theFightRequestFailedToFindAnOpponent(): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertSame(404, $this->response->httpCode());
        Assert::assertSame(0, self::database()->fetchOne('SELECT count(1) FROM fights'));
    }
}
