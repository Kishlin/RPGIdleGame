<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\Context;

use Kishlin\Tests\Apps\RPGIdleGame\Backend\Tools\HTTPClient\Request;
use PHPUnit\Framework\Assert;

final class FightAccessContext extends RPGIdleGameAPIContext
{
    /**
     * @When /^a client asks to view one the fight's infos$/
     */
    public function aClientAsksToViewOneTheFightsInfos(): void
    {
        $this->response = null;
        $this->response = self::client()->get(new Request(
            uri: '/fight/' . self::FIGHT_UUID,
            headers: [
                'Cookie: token=' . self::AUTHENTICATION_FOR_CLIENT,
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
                'Cookie: token=' . self::AUTHENTICATION_FOR_CLIENT,
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
                'Cookie: token=' . self::AUTHENTICATION_FOR_STRANGER,
            ],
        ));
    }

    /**
     * @When /^a client asks to view the fights of its character$/
     */
    public function aClientAsksToViewTheFightsOfItsCharacter(): void
    {
        $this->response = null;
        $this->response = self::client()->get(new Request(
            uri: '/fight/all/' . self::FIGHTER_UUID,
            headers: [
                'Cookie: token=' . self::AUTHENTICATION_FOR_CLIENT,
            ],
        ));
    }

    /**
     * @When /^a stranger asks to view the fights of a client's character$/
     */
    public function aStrangerAsksToViewTheFightsOfAClientsCharacter(): void
    {
        $this->response = null;
        $this->response = self::client()->get(new Request(
            uri: '/fight/all/' . self::FIGHTER_UUID,
            headers: [
                'Cookie: token=' . self::AUTHENTICATION_FOR_STRANGER,
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

    /**
     * @Then /^details about all the fights were returned$/
     */
    public function detailsAboutAllTheFightsWereReturned(): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertSame(200, $this->response->httpCode());

        $content = $this->response->decodedBody();

        Assert::assertIsArray($content);
        Assert::assertCount(3, $content);
    }

    /**
     * @Then /^it gets a response with an empty fight list$/
     */
    public function itGetsAResponseWithAnEmptyFightList(): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertSame(200, $this->response->httpCode());

        $content = $this->response->decodedBody();

        Assert::assertIsArray($content);
        Assert::assertCount(0, $content);
    }

    /**
     * @Then /^the query for all the fights was refused$/
     */
    public function theQueryForAllTheFightsWasRefused(): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertSame(404, $this->response->httpCode());
    }
}
