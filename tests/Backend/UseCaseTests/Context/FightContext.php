<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context;

use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterAttack;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterDefense;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterHealth;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterMagik;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterName;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterRank;
use Kishlin\Backend\RPGIdleGame\Fight\Application\InitiateAFight\InitiateAFightCommand;
use Kishlin\Backend\RPGIdleGame\Fight\Application\InitiateAFight\RequesterIsNotAllowedToInitiateFight;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\NoOpponentAvailableException;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightId;
use Kishlin\Tests\Backend\Tools\ReflectionHelper;
use PHPUnit\Framework\Assert;
use ReflectionException;
use Throwable;

final class FightContext extends RPGIdleGameContext
{
    private const CLIENT_UUID   = '97c116cc-21b0-4624-8e02-88b9b1a977a7';
    private const STRANGER_UUID = 'df42d3aa-10ea-4ca3-936b-2bba5ae16fe6';
    private const FIGHTER_UUID  = 'fa2e098a-1ed4-4ddb-91d1-961e0af7143b';
    private const OPPONENT_UUID = 'e26b33be-5253-4cc3-8480-a15e80f18b7a';

    private ?FightId   $fightId         = null;
    private ?Throwable $exceptionThrown = null;

    /**
     * @Given /^there is an opponent available$/
     *
     * @throws ReflectionException
     */
    public function thereIsAnOpponentAvailable(): void
    {
        $opponent = Character::createFresh(
            new CharacterId(self::OPPONENT_UUID),
            new CharacterName('Opponent'),
            new CharacterOwner(self::STRANGER_UUID),
        );

        ReflectionHelper::writePropertyValue($opponent, 'characterHealth', new CharacterHealth(70));
        ReflectionHelper::writePropertyValue($opponent, 'characterAttack', new CharacterAttack(48));
        ReflectionHelper::writePropertyValue($opponent, 'characterDefense', new CharacterDefense(28));
        ReflectionHelper::writePropertyValue($opponent, 'characterMagik', new CharacterMagik(30));
        ReflectionHelper::writePropertyValue($opponent, 'characterRank', new CharacterRank(120));

        self::container()->characterGatewaySpy()->save($opponent);
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
        try {
            $response = self::container()->commandBus()->execute(
                InitiateAFightCommand::fromScalars(
                    fighterId: self::FIGHTER_UUID,
                    requesterId: self::CLIENT_UUID,
                )
            );

            assert($response instanceof FightId);

            $this->fightId         = $response;
            $this->exceptionThrown = null;
        } catch (Throwable $e) {
            $this->exceptionThrown = $e;
        }
    }

    /**
     * @When /^a stranger tries to fight with the client's character$/
     */
    public function aStrangerTriesToFightWithTheClientSCharacter(): void
    {
        try {
            self::container()->commandBus()->execute(
                InitiateAFightCommand::fromScalars(
                    fighterId: self::STRANGER_UUID,
                    requesterId: self::CLIENT_UUID,
                )
            );

            $this->exceptionThrown = null;
        } catch (Throwable $e) {
            $this->exceptionThrown = $e;
        }
    }

    /**
     * @Then /^the fight is registered$/
     */
    public function theFightIsRegistered(): void
    {
        Assert::assertNotNull($this->fightId);
        Assert::assertTrue(self::container()->fightGatewaySpy()->has($this->fightId->value()));
        Assert::assertNotEmpty(self::container()->fightGatewaySpy()->findOneById($this->fightId)?->turns());
    }

    /**
     * @Then /^the fight request was refused$/
     */
    public function theFightRequestWasRefused(): void
    {
        Assert::assertNotNull($this->exceptionThrown);
        Assert::assertInstanceOf(RequesterIsNotAllowedToInitiateFight::class, $this->exceptionThrown);
    }

    /**
     * @Then /^the fight request failed to find an opponent$/
     */
    public function theFightRequestFailedToFindAnOpponent(): void
    {
        Assert::assertNotNull($this->exceptionThrown);
        Assert::assertInstanceOf(NoOpponentAvailableException::class, $this->exceptionThrown);
    }
}
