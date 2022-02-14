<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\Context;

use Kishlin\Tests\Apps\RPGIdleGame\Backend\Tools\HTTPClient\Request;
use PHPUnit\Framework\Assert;

final class CharacterManagementContext extends RPGIdleGameAPIContext
{
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
     * @Then the character is registered
     */
    public function theCharacterIsRegistered(): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertSame(201, $this->response->httpCode());

        $data = $this->response->decodedBody();

        Assert::assertIsArray($data);
        Assert::assertArrayHasKey('id', $data);

        $countIdDatabase = self::database()->fetchOne(
            'SELECT count(1) FROM characters WHERE id = :id',
            ['id' => $data['id']],
        );

        Assert::assertSame(1, $countIdDatabase);
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
        Assert::assertSame(200, $this->response->httpCode());

        $data = $this->response->decodedBody();

        Assert::assertIsArray($data);
        Assert::assertArrayHasKey('id', $data);
        Assert::assertSame(self::FIGHTER_UUID, $data['id']);

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
