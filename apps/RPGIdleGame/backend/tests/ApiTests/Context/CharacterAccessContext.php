<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\Context;

use Kishlin\Tests\Apps\RPGIdleGame\Backend\Tools\HTTPClient\Request;
use PHPUnit\Framework\Assert;

final class CharacterAccessContext extends RPGIdleGameAPIContext
{
    /**
     * @When /^a client asks to read one of its character's infos$/
     */
    public function aClientAsksToReadOneOfItsCharactersInfos(): void
    {
        $this->response = null;
        $this->response = self::client()->get(new Request(
            uri: '/character/' . self::FIGHTER_UUID,
            headers: [
                'Cookie: token=' . self::AUTHENTICATION_FOR_CLIENT,
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
                'Cookie: token=' . self::AUTHENTICATION_FOR_CLIENT,
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
                'Cookie: token=' . self::AUTHENTICATION_FOR_STRANGER,
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
                'Cookie: token=' . self::AUTHENTICATION_FOR_CLIENT,
            ],
        ));
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
