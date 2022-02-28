<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context;

use DateTimeImmutable;
use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
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
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightDate;
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
use PHPUnit\Framework\Assert;
use Throwable;

final class FightContext extends RPGIdleGameContext
{
    private ?FightId   $fightId         = null;
    private ?Response  $response        = null;
    private ?Throwable $exceptionThrown = null;

    /**
     * @Given /^there is no available opponent$/
     */
    public function thereIsNoAvailableOpponent(): void
    {
    }

    /**
     * @Given /^its character took part in a fight with the opponent$/
     */
    public function itsCharacterTookPartInAFight(): void
    {
        self::computeAndSaveAFight(self::FIGHT_ID);
    }

    /**
     * @Given /^its character took part in a few fights$/
     */
    public function itsCharacterTookPartInAFewFights(): void
    {
        self::computeAndSaveAFight('cfc086d2-31e8-423f-bea9-ba8b03ff5c9f');
        self::computeAndSaveAFight('9ec7adff-4d95-49c6-9fc0-da242a4c521f');
    }

    /**
     * @Given /^its character did not take part in any fights$/
     */
    public function itsCharacterDidNotTakePartInAnyFights(): void
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
     * @When /^a client asks to view one the fight's infos$/
     */
    public function aClientAsksToViewOneTheFightSInfos(): void
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
     * @When /^a client asks to view a fight that does not exist$/
     */
    public function aClientAsksToViewAFightThatDoesNotExist(): void
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
     * @When /^a stranger tries to view the fight's infos$/
     */
    public function aStrangerTriesToViewTheFightSInfos(): void
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
        /** @var ViewFightsForFighterResponse $response */
        $response = $this->response;

        Assert::assertNotNull($this->response);
        Assert::assertInstanceOf(ViewFightsForFighterResponse::class, $this->response);
        Assert::assertCount(2, $response->fights()->toArray());
    }

    /**
     * @Then /^it gets a response with an empty fight list$/
     */
    public function itGetsAResponseWithAnEmptyFightList(): void
    {
        /** @var ViewFightsForFighterResponse $response */
        $response = $this->response;

        Assert::assertNotNull($this->response);
        Assert::assertInstanceOf(ViewFightsForFighterResponse::class, $this->response);
        Assert::assertEmpty($response->fights()->toArray());
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

    private static function computeAndSaveAFight(string $fightId): void
    {
        $dice          = new RandomDice();
        $uuidGenerator = new UuidGeneratorUsingRamsey();

        $fight = Fight::initiate(
            new FightId($fightId),
            FightInitiator::create(
                new CharacterId(self::FIGHTER_UUID),
                new FightParticipantId($uuidGenerator->uuid4()),
                new FightParticipantHealth(80),
                new FightParticipantAttack(56),
                new FightParticipantDefense(23),
                new FightParticipantMagik(34),
                new FightParticipantRank(125),
            ),
            FightOpponent::create(
                new CharacterId(self::OPPONENT_UUID),
                new FightParticipantId($uuidGenerator->uuid4()),
                new FightParticipantHealth(70),
                new FightParticipantAttack(48),
                new FightParticipantDefense(28),
                new FightParticipantMagik(30),
                new FightParticipantRank(120),
            ),
            new FightDate(new DateTimeImmutable()),
        );

        $fight->unfold($dice, $uuidGenerator);

        self::container()->fightGatewaySpy()->save($fight);
    }
}
