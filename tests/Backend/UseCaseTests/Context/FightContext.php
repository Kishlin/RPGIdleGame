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
use Kishlin\Backend\RPGIdleGame\Fight\Application\ViewFight\ViewFightQuery;
use Kishlin\Backend\RPGIdleGame\Fight\Application\ViewFight\ViewFightResponse;
use Kishlin\Backend\RPGIdleGame\Fight\Application\ViewFightsForCharacter\ViewFightsForFighterQuery;
use Kishlin\Backend\RPGIdleGame\Fight\Application\ViewFightsForCharacter\ViewFightsForFighterResponse;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\CannotAccessFightsException;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\Fight;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightInitiator;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightNotFoundException;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightOpponent;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\NoOpponentAvailableException;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightId;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantAttack;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantDefense;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantHealth;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantId;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantMagik;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantRank;
use Kishlin\Backend\RPGIdleGame\Fight\Infrastructure\RandomDice;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;
use Kishlin\Backend\Shared\Infrastructure\Randomness\UuidGeneratorUsingRamsey;
use Kishlin\Tests\Backend\Tools\ReflectionHelper;
use PHPUnit\Framework\Assert;
use ReflectionException;
use Throwable;

final class FightContext extends RPGIdleGameContext
{
    private const CLIENT_UUID          = '97c116cc-21b0-4624-8e02-88b9b1a977a7';
    private const STRANGER_UUID        = 'df42d3aa-10ea-4ca3-936b-2bba5ae16fe6';
    private const FIGHTER_UUID         = 'fa2e098a-1ed4-4ddb-91d1-961e0af7143b';
    private const OPPONENT_UUID        = 'e26b33be-5253-4cc3-8480-a15e80f18b7a';
    private const FIGHT_ID             = '695fbddb-1863-4170-85ba-c0f146b341ad';
    private const FIGHTER_INITIATOR_ID = '4d248f5f-5f10-49ed-a921-ccdc383acdaf';

    private ?FightId   $fightId         = null;
    private ?Response  $response        = null;
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
     * @Given /^its character takes part in a fight with the opponent$/
     */
    public function itsCharacterTookPartInAFight(): void
    {
        $fight = Fight::initiate(
            new FightId(self::FIGHT_ID),
            FightInitiator::create(
                new CharacterId(self::FIGHTER_UUID),
                new FightParticipantId(self::FIGHTER_INITIATOR_ID),
                new FightParticipantHealth(80),
                new FightParticipantAttack(56),
                new FightParticipantDefense(23),
                new FightParticipantMagik(34),
                new FightParticipantRank(125),
            ),
            FightOpponent::create(
                new CharacterId(self::OPPONENT_UUID),
                new FightParticipantId('051a001a-0a0e-4f71-a0bb-6f6466ad8995'),
                new FightParticipantHealth(70),
                new FightParticipantAttack(48),
                new FightParticipantDefense(28),
                new FightParticipantMagik(30),
                new FightParticipantRank(120),
            ),
        );

        $fight->unfold(new RandomDice(), new UuidGeneratorUsingRamsey());

        self::container()->fightGatewaySpy()->save($fight);
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
     * @When /^a client asks to read one the fight's infos$/
     */
    public function aClientAsksToReadOneTheFightSInfos(): void
    {
        try {
            $this->response = null;
            $this->response = self::container()->queryBus()->ask(
                ViewFightQuery::fromScalars(
                    fightId: self::FIGHT_ID,
                    requesterId: self::CLIENT_UUID
                )
            );

            $this->exceptionThrown = null;
        } catch (Throwable $e) {
            $this->exceptionThrown = $e;
        }
    }

    /**
     * @When /^a client asks to read a fight that does not exist$/
     */
    public function aClientAsksToReadAFightThatDoesNotExist(): void
    {
        try {
            $this->response = null;
            $this->response = self::container()->queryBus()->ask(
                ViewFightQuery::fromScalars(
                    fightId: 'invalid-id',
                    requesterId: self::CLIENT_UUID
                )
            );

            $this->exceptionThrown = null;
        } catch (Throwable $e) {
            $this->exceptionThrown = $e;
        }
    }

    /**
     * @When /^a stranger tries to read the fight's infos$/
     */
    public function aStrangerTriesToReadTheFightSInfos(): void
    {
        try {
            $this->response = null;
            $this->response = self::container()->queryBus()->ask(
                ViewFightQuery::fromScalars(
                    fightId: self::FIGHT_ID,
                    requesterId: self::STRANGER_UUID
                )
            );

            $this->exceptionThrown = null;
        } catch (Throwable $e) {
            $this->exceptionThrown = $e;
        }
    }

    /**
     * @When /^a client asks to view the fights of its character$/
     */
    public function aClientAsksToViewTheFightsOfItsCharacter(): void
    {
        try {
            $this->response = null;
            $this->response = self::container()->queryBus()->ask(
                ViewFightsForFighterQuery::fromScalars(
                    fighterId: self::FIGHTER_UUID,
                    requesterId: self::CLIENT_UUID,
                )
            );

            $this->exceptionThrown = null;
        } catch (Throwable $e) {
            $this->exceptionThrown = $e;
        }
    }

    /**
     * @When /^a stranger asks to view the fights of a client's character$/
     */
    public function aStrangerAsksToViewTheFightsOfAClientSCharacter(): void
    {
        try {
            $this->response = null;
            $this->response = self::container()->queryBus()->ask(
                ViewFightsForFighterQuery::fromScalars(
                    fighterId: self::FIGHTER_UUID,
                    requesterId: self::STRANGER_UUID,
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

    /**
     * @Then /^details about the fight were returned$/
     */
    public function detailsAboutTheFightWereReturned(): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertInstanceOf(ViewFightResponse::class, $this->response);
    }

    /**
     * @Then /^the query for the fight infos was refused$/
     */
    public function theQueryForTheFightInfosWasRefused(): void
    {
        Assert::assertNull($this->response);
        Assert::assertNotNull($this->exceptionThrown);
        Assert::assertInstanceOf(FightNotFoundException::class, $this->exceptionThrown);
    }

    /**
     * @Then /^details about all the fights were returned$/
     */
    public function detailsAboutAllTheFightsWereReturned(): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertInstanceOf(ViewFightsForFighterResponse::class, $this->response);
    }

    /**
     * @Then /^the query for all the fights was refused$/
     */
    public function theQueryForAllTheFightsWasRefused(): void
    {
        Assert::assertNull($this->response);
        Assert::assertNotNull($this->exceptionThrown);
        Assert::assertInstanceOf(CannotAccessFightsException::class, $this->exceptionThrown);
    }
}
