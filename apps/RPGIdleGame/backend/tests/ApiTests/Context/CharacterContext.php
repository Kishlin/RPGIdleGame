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
     * @Then the character is registered
     */
    public function theCharacterIsRegistered(): void
    {
        Assert::assertNotNull($this->characterId);
        $count = self::database()->fetchOne('SELECT count(1) FROM characters WHERE id = :id', ['id' => $this->characterId]);
        Assert::assertSame(1, $count);
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
     * @Then the creation was refused
     */
    public function theCreationWasRefused(): void
    {
        Assert::assertNull($this->characterId);
        Assert::assertSame(403, $this->response?->httpCode());
        Assert::assertSame(0, self::database()->fetchOne('SELECT count(1) FROM characters'));
    }
}
