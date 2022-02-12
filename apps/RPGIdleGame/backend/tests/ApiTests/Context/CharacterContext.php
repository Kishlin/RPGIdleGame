<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\Context;

use Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\HTTPClient\Request;
use PHPUnit\Framework\Assert;

final class CharacterContext extends RPGIdleGameAPIContext
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
     * @Given /^it owns a few characters$/
     */
    public function itOwnsAFewCharacters(): void
    {
        $queries = [
            <<<'SQL'
INSERT INTO characters (id, owner, name, skill_points, health, attack, defense, magik, rank, fights_count, wins_count, draws_count, losses_count)
VALUES ('88c7ee64-27e5-409c-b8a2-0db1aba2131b', :owner, 'Fighter', 12, 10, 0, 0, 0, 0, 0, 0, 0, 0);
SQL,
            <<<'SQL'
INSERT INTO characters (id, owner, name, skill_points, health, attack, defense, magik, rank, fights_count, wins_count, draws_count, losses_count)
VALUES ('0c023953-0bfd-43ac-81f9-e608cef3e3f6', :owner, 'Brawler', 24, 56, 28, 13, 26, 125, 68, 30, 5, 33);
SQL,
            <<<'SQL'
INSERT INTO characters (id, owner, name, skill_points, health, attack, defense, magik, rank, fights_count, wins_count, draws_count, losses_count)
VALUES ('5a4440cf-4ebf-4f6a-a356-fee321f719ed', :owner, 'Magician', 13, 128, 64, 52, 35, 226, 1688, 824, 113, 751);
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

    /**
     * @When a client creates a character
     */
    public function aClientCreatesACharacter(): void
    {
        $this->response = null;
        $this->response = self::client()->post(new Request(
            uri: '/character/create',
            headers: [
                'Content-Type: application/json',
                'Authorization: Bearer ' . self::AUTHENTICATION_FOR_CLIENT,
            ],
            params: ['characterName' => 'Kishlin']
        ));
    }

    /**
     * @When /^a client deletes its character$/
     */
    public function aClientDeletesItsCharacter(): void
    {
        $this->response = null;
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
        $this->response = null;
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
        $this->response = null;
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
        $this->response = null;
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
        $this->response = null;
        $this->response = self::client()->put(new Request(
            uri: '/character/' . self::FIGHTER_UUID,
            headers: [
                'Authorization: Bearer ' . self::AUTHENTICATION_FOR_STRANGER,
            ],
            params: ['health' => 1, 'attack' => 0, 'defense' => 0, 'magik' => 0],
        ));
    }

    /**
     * @When /^a client asks to read one of its character's infos$/
     */
    public function aClientAsksToReadOneOfItsCharactersInfos(): void
    {
        $this->response = null;
        $this->response = self::client()->get(new Request(
            uri: '/character/' . self::FIGHTER_UUID,
            headers: [
                'Authorization: Bearer ' . self::AUTHENTICATION_FOR_CLIENT,
            ],
        ));
    }

    /**
     * @When /^a client asks to read a character that does not exist$/
     */
    public function aClientAsksToReadACharacterThatDoesNotExist(): void
    {
        $this->response = null;
        $this->response = self::client()->get(new Request(
            uri: '/character/character-that-does-exist',
            headers: [
                'Authorization: Bearer ' . self::AUTHENTICATION_FOR_CLIENT,
            ],
        ));
    }

    /**
     * @When /^a stranger tries to read its character$/
     */
    public function aStrangerTriesToReadItsCharacter(): void
    {
        $this->response = null;
        $this->response = self::client()->get(new Request(
            uri: '/character/' . self::FIGHTER_UUID,
            headers: [
                'Authorization: Bearer ' . self::AUTHENTICATION_FOR_STRANGER,
            ],
        ));
    }

    /**
     * @When /^a client asks to read all of its characters$/
     */
    public function aClientAsksToReadAllOfItsCharacters(): void
    {
        $this->response = null;
        $this->response = self::client()->get(new Request(
            uri: '/character/all',
            headers: [
                'Authorization: Bearer ' . self::AUTHENTICATION_FOR_CLIENT,
            ],
        ));
    }

    /**
     * @Then the character is registered
     */
    public function theCharacterIsRegistered(): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertSame(201, $this->response->httpCode());

        $data = $this->response->decodedBody();

        Assert::assertIsArray($data);
        Assert::assertArrayHasKey('characterId', $data);

        Assert::assertSame(
            1,
            self::database()->fetchOne('SELECT count(1) FROM characters WHERE id = :id', ['id' => $data['characterId']]),
        );
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

    /**
     * @Then /^details about the character were returned$/
     */
    public function detailsAboutTheCharacterWereReturned(): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertSame(200, $this->response->httpCode());

        $content = $this->response->decodedBody();

        Assert::assertIsArray($content);
        Assert::assertArrayHasKey('id', $content);
        Assert::assertSame(self::FIGHTER_UUID, $content['id']);
    }

    /**
     * @Then /^the list of all of its characters was returned$/
     */
    public function theListOfAllOfItsCharactersWasReturned(): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertSame(200, $this->response->httpCode());

        $content = $this->response->decodedBody();

        Assert::assertIsArray($content);
        Assert::assertCount(3, $content);

        foreach ($content as $shouldBeACharacter) {
            Assert::assertEmpty(array_diff(['id', 'owner', 'name'], array_keys($shouldBeACharacter)));
        }
    }

    /**
     * @Then /^the query was refused$/
     */
    public function theQueryWasRefused(): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertSame(403, $this->response->httpCode());
    }
}
