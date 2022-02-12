<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\Context;

use Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\HTTPClient\Request;
use PHPUnit\Framework\Assert;

final class CharacterContext extends RPGIdleGameAPIContext
{
    private ?string $characterId = null;

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
INSERT INTO characters (id, owner, name, skill_points, health, attack, defense, magik, rank)
VALUES (:id, :owner, 'Kishlin', 12, 10, 0, 0, 0, 0);
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
INSERT INTO characters (id, owner, name, skill_points, health, attack, defense, magik, rank)
VALUES (:id, :owner, 'Kishlin', 3000, 80, 56, 23, 34, 125);
SQL;

        self::database()->exec($query, ['id' => self::FIGHTER_UUID, 'owner' => self::CLIENT_UUID]);

        self::database()->exec(
            'UPDATE character_counts SET character_count = character_count + 1 WHERE owner_id = :owner;',
            ['owner' => self::CLIENT_UUID],
        );
    }

    /**
     * @When a client creates a character
     */
    public function aClientCreatesACharacter(): void
    {
        $this->response = self::client()->post(new Request(
            uri: '/character/create',
            headers: [
                'Content-Type: application/json',
                'Authorization: Bearer ' . self::AUTHENTICATION_FOR_CLIENT,
            ],
            params: ['characterName' => 'Kishlin']
        ));

        if (201 === $this->response->httpCode()) {
            /** @var array{characterId: string} $decodedBody */
            $decodedBody       = $this->response->decodedBody();
            $this->characterId = $decodedBody['characterId'];
        } else {
            $this->characterId = null;
        }
    }

    /**
     * @When /^a client deletes its character$/
     */
    public function aClientDeletesItsCharacter(): void
    {
        $this->response = self::client()->delete(new Request(
            uri: '/character/' . self::FIGHTER_UUID,
            headers: [
                'Authorization: Bearer ' . self::AUTHENTICATION_FOR_CLIENT,
            ],
        ));
    }

    /**
     * @When /^a stranger tries to delete the client's character$/
     */
    public function aStrangerTriesToDeleteTheClientsCharacter(): void
    {
        $this->response = self::client()->delete(new Request(
            uri: '/character/' . self::FIGHTER_UUID,
            headers: [
                'Authorization: Bearer ' . self::AUTHENTICATION_FOR_STRANGER,
            ],
        ));
    }

    /**
     * @When /^a client distributes some skill points$/
     */
    public function aClientDistributesSomeSkillPoints(): void
    {
        $this->response = self::client()->put(new Request(
            uri: '/character/' . self::FIGHTER_UUID,
            headers: [
                'Authorization: Bearer ' . self::AUTHENTICATION_FOR_CLIENT,
            ],
            params: ['health' => 85, 'attack' => 92, 'defense' => 35, 'magik' => 56],
        ));
    }

    /**
     * @When /^a client tries to distribute more skill points than available$/
     */
    public function aClientTriesToDistributeMoreSkillPointsThanAvailable(): void
    {
        $this->response = self::client()->put(new Request(
            uri: '/character/' . self::FIGHTER_UUID,
            headers: [
                'Authorization: Bearer ' . self::AUTHENTICATION_FOR_CLIENT,
            ],
            params: ['health' => 200, 'attack' => 300, 'defense' => 0, 'magik' => 0],
        ));
    }

    /**
     * @When /^a stranger tries to distribute skill points to its character$/
     */
    public function aStrangerTriesToDistributeSkillPointsToItsCharacter(): void
    {
        $this->response = self::client()->put(new Request(
            uri: '/character/' . self::FIGHTER_UUID,
            headers: [
                'Authorization: Bearer ' . self::AUTHENTICATION_FOR_STRANGER,
            ],
            params: ['health' => 1, 'attack' => 0, 'defense' => 0, 'magik' => 0],
        ));
    }

    /**
     * @Then the character is registered
     */
    public function theCharacterIsRegistered(): void
    {
        Assert::assertNotNull($this->characterId);
        $count = self::database()->fetchOne('SELECT count(1) FROM characters WHERE id = :id', ['id' => $this->characterId]);
        Assert::assertSame(1, $count);
    }

    /**
     * @Then /^the character is deleted$/
     */
    public function theCharacterIsDeleted(): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertSame(204, $this->response->httpCode());
        Assert::assertSame(0, self::database()->fetchOne('SELECT count(1) FROM characters;'));
    }

    /**
     * @Then the character count is incremented
     */
    public function theCharacterCountIsIncremented(): void
    {
        Assert::assertNotNull($this->characterId);
        $count = self::database()->fetchOne(
            'SELECT character_count FROM character_counts WHERE owner_id = :owner',
            ['owner' => self::CLIENT_UUID]
        );
        Assert::assertSame(1, $count);
    }

    /**
     * @Then /^the character count is decremented$/
     */
    public function theCharacterCountIsDecremented(): void
    {
        $count = self::database()->fetchOne(
            'SELECT character_count FROM character_counts WHERE owner_id = :owner',
            ['owner' => self::CLIENT_UUID]
        );

        Assert::assertSame(0, $count);
    }

    /**
     * @Then /^the character stats are updated as wanted$/
     */
    public function theCharacterStatsAreUpdatedAsWanted(): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertSame(204, $this->response->httpCode());

        $data = self::database()->fetchAssociative(
            'SELECT skill_points, health, attack, defense, magik FROM characters WHERE id = :id',
            ['id' => self::FIGHTER_UUID]
        );

        Assert::assertIsArray($data);

        Assert::assertSame(5, $data['skill_points']);
        Assert::assertSame(165, $data['health']);
        Assert::assertSame(148, $data['attack']);
        Assert::assertSame(58, $data['defense']);
        Assert::assertSame(90, $data['magik']);
    }

    /**
     * @Then the creation was refused
     */
    public function theCreationWasRefused(): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertSame(403, $this->response->httpCode());
        Assert::assertSame(0, self::database()->fetchOne('SELECT count(1) FROM characters'));
    }

    /**
     * @Then /^the deletion was refused$/
     */
    public function theDeletionWasRefused(): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertSame(403, $this->response->httpCode());
    }

    /**
     * @Then /^the stats update was refused$/
     */
    public function theStatsUpdateWasRefused(): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertSame(403, $this->response->httpCode());
    }

    /**
     * @Then /^the stats update was denied$/
     */
    public function theStatsUpdateWasDenied(): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertSame(403, $this->response->httpCode());
    }
}
