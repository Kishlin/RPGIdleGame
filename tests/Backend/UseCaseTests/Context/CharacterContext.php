<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context;

use Behat\Behat\Context\Context;
use Kishlin\Backend\Account\Domain\Account;
use Kishlin\Backend\Account\Domain\ValueObject\AccountEmail;
use Kishlin\Backend\Account\Domain\ValueObject\AccountId;
use Kishlin\Backend\Account\Domain\ValueObject\AccountPassword;
use Kishlin\Backend\Account\Domain\ValueObject\AccountUsername;
use Kishlin\Backend\RPGIdleGame\Character\Application\CreateCharacter\CreateCharacterCommand;
use Kishlin\Backend\RPGIdleGame\Character\Application\CreateCharacter\HasReachedCharacterLimitException;
use Kishlin\Backend\RPGIdleGame\Character\Application\DeleteCharacter\DeleteCharacterCommand;
use Kishlin\Backend\RPGIdleGame\Character\Application\DeleteCharacter\DeletionIsNotAllowedException;
use Kishlin\Backend\RPGIdleGame\Character\Application\DistributeSkillPoints\CharacterNotFoundException;
use Kishlin\Backend\RPGIdleGame\Character\Application\DistributeSkillPoints\DistributeSkillPointsCommand;
use Kishlin\Backend\RPGIdleGame\Character\Application\ViewAllCharacter\ViewAllCharactersQuery;
use Kishlin\Backend\RPGIdleGame\Character\Application\ViewAllCharacter\ViewAllCharactersResponse;
use Kishlin\Backend\RPGIdleGame\Character\Application\ViewCharacter\ViewCharacterQuery;
use Kishlin\Backend\RPGIdleGame\Character\Application\ViewCharacter\ViewCharacterResponse;
use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
use Kishlin\Backend\RPGIdleGame\Character\Domain\NotEnoughSkillPointsException;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterAttack;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterDefense;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterHealth;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterMagik;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterName;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterSkillPoint;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCount;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountOwner;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;
use Kishlin\Tests\Backend\Tools\ReflectionHelper;
use PHPUnit\Framework\Assert;
use ReflectionException;
use Throwable;

final class CharacterContext extends RPGIdleGameContext implements Context
{
    private const CLIENT_UUID    = '97c116cc-21b0-4624-8e02-88b9b1a977a7';
    private const STRANGER_UUID  = 'df42d3aa-10ea-4ca3-936b-2bba5ae16fe6';
    private const CHARACTER_UUID = 'fa2e098a-1ed4-4ddb-91d1-961e0af7143b';

    private ?CharacterId $characterId   = null;
    private ?Throwable $exceptionThrown = null;
    private ?Response $response         = null;

    /**
     * @Given /^a client has an account$/
     */
    public function aClientHasAnAccount(): void
    {
        self::container()->accountGatewaySpy()->save(Account::createActiveAccount(
            new AccountId(self::CLIENT_UUID),
            new AccountUsername('User'),
            new AccountPassword('password'),
            new AccountEmail('email@example.com'),
        ));

        self::container()->characterCountGatewaySpy()->save(CharacterCount::createForOwner(
            new CharacterCountOwner(self::CLIENT_UUID),
        ));
    }

    /**
     * @Given /^it has reached the character limit$/
     *
     * @throws ReflectionException
     */
    public function itHasReachedTheCharacterLimit(): void
    {
        $this
            ->container()
            ->characterCountGatewaySpy()
            ->manuallyOverrideCountForOwner(new AccountId(self::CLIENT_UUID), CharacterCount::CHARACTER_LIMIT)
        ;
    }

    /**
     * @Given /^it owns a character$/
     */
    public function itOwnsACharacter(): void
    {
        $this->addCharacterToDatabase(Character::createFresh(
            new CharacterId(self::CHARACTER_UUID),
            new CharacterName('Kishlin'),
            new CharacterOwner(self::CLIENT_UUID),
        ));
    }

    /**
     * @Given /^it owns a well advanced character$/
     *
     * @throws ReflectionException
     */
    public function itOwnsAWellAdvancedCharacter(): void
    {
        $character = Character::createFresh(
            new CharacterId(self::CHARACTER_UUID),
            new CharacterName('Kishlin'),
            new CharacterOwner(self::CLIENT_UUID),
        );

        ReflectionHelper::writePropertyValue($character, 'characterSkillPoint', new CharacterSkillPoint(3000));
        ReflectionHelper::writePropertyValue($character, 'characterHealth', new CharacterHealth(80));
        ReflectionHelper::writePropertyValue($character, 'characterAttack', new CharacterAttack(23));
        ReflectionHelper::writePropertyValue($character, 'characterDefense', new CharacterDefense(56));
        ReflectionHelper::writePropertyValue($character, 'characterMagik', new CharacterMagik(34));

        $this->addCharacterToDatabase($character);
    }

    /**
     * @Given /^it owns a few characters$/
     */
    public function itOwnsAFewCharacters(): void
    {
        $charactersUuid = [
            'bd722166-07b3-4ff8-90d9-717d316f49be' => 'Gandalf',
            '105d85a5-a345-4e74-a883-c80306718ab9' => 'Aragorn',
            'fcf60934-f4a2-404c-a893-70af30837974' => 'Legolas',
        ];

        foreach ($charactersUuid as $characterUuid => $characterName) {
            $this->addCharacterToDatabase(Character::createFresh(
                new CharacterId($characterUuid),
                new CharacterName($characterName),
                new CharacterOwner(self::CLIENT_UUID),
            ));
        }
    }

    /**
     * @When /^a client creates a character$/
     */
    public function aClientCreatesACharacter(): void
    {
        try {
            $response = self::container()->commandBus()->execute(
                CreateCharacterCommand::fromScalars(
                    characterId: self::CHARACTER_UUID,
                    characterName: 'Kishlin',
                    ownerUuid: self::CLIENT_UUID,
                )
            );

            assert($response instanceof CharacterId);

            $this->characterId     = $response;
            $this->exceptionThrown = null;
        } catch (HasReachedCharacterLimitException $e) {
            $this->exceptionThrown = $e;
        }
    }

    /**
     * @When /^a client distributes some skill points$/
     */
    public function aClientDistributesSomeSkillPoints(): void
    {
        try {
            self::container()->commandBus()->execute(
                DistributeSkillPointsCommand::fromScalars(
                    characterId: self::CHARACTER_UUID,
                    requesterId: self::CLIENT_UUID,
                    healthPointsToAdd: 85,
                    attackPointsToAdd: 35,
                    defensePointsToAdd: 92,
                    magikPointsToAdd: 56,
                )
            );

            $this->exceptionThrown = null;
        } catch (Throwable $e) {
            $this->exceptionThrown = $e;
        }
    }

    /**
     * @When /^a client tries to distribute more skill points than available$/
     */
    public function aClientTriesToDistributeMoreSkillPointsThanAvailable(): void
    {
        try {
            self::container()->commandBus()->execute(
                DistributeSkillPointsCommand::fromScalars(
                    characterId: self::CHARACTER_UUID,
                    requesterId: self::CLIENT_UUID,
                    healthPointsToAdd: 200,
                    attackPointsToAdd: 300,
                    defensePointsToAdd: 0,
                    magikPointsToAdd: 0,
                )
            );

            $this->exceptionThrown = null;
        } catch (Throwable $e) {
            $this->exceptionThrown = $e;
        }
    }

    /**
     * @When /^a stranger tries to distribute skill points to its character$/
     */
    public function aStrangerTriesToDistributeSkillPointsToItsCharacter(): void
    {
        try {
            self::container()->commandBus()->execute(
                DistributeSkillPointsCommand::fromScalars(
                    characterId: self::CHARACTER_UUID,
                    requesterId: self::STRANGER_UUID,
                    healthPointsToAdd: 1,
                    attackPointsToAdd: 0,
                    defensePointsToAdd: 0,
                    magikPointsToAdd: 0,
                )
            );

            $this->exceptionThrown = null;
        } catch (Throwable $e) {
            $this->exceptionThrown = $e;
        }
    }

    /**
     * @When /^a client deletes its character$/
     */
    public function aClientDeletesItsCharacter(): void
    {
        try {
            $response = self::container()->commandBus()->execute(
                DeleteCharacterCommand::fromScalars(
                    characterId: self::CHARACTER_UUID,
                    accountRequestingDeletionUuid: self::CLIENT_UUID,
                )
            );

            Assert::assertTrue($response);

            $this->exceptionThrown = null;
        } catch (Throwable $e) {
            $this->exceptionThrown = $e;
        }
    }

    /**
     * @When /^a stranger tries to delete the client's character$/
     */
    public function aClientDeletesACharacterOwnedByAnotherAccount(): void
    {
        try {
            self::container()->commandBus()->execute(
                DeleteCharacterCommand::fromScalars(
                    characterId: self::CHARACTER_UUID,
                    accountRequestingDeletionUuid: self::STRANGER_UUID,
                )
            );

            $this->exceptionThrown = null;
        } catch (Throwable $e) {
            $this->exceptionThrown = $e;
        }
    }

    /**
     * @When /^a client asks to read one of its character's infos$/
     */
    public function aClientAsksToReadCharacterInfos(): void
    {
        try {
            $this->response = self::container()->queryBus()->ask(
                ViewCharacterQuery::fromScalars(
                    characterId: self::CHARACTER_UUID,
                    requesterId: self::CLIENT_UUID
                )
            );

            $this->exceptionThrown = null;
        } catch (Throwable $e) {
            $this->exceptionThrown = $e;
        }
    }

    /**
     * @When /^a client asks to read a character that does not exist$/
     */
    public function aClientAsksToReadACharacterThatDoesNotExist(): void
    {
        try {
            $this->response = self::container()->queryBus()->ask(
                ViewCharacterQuery::fromScalars(
                    characterId: self::CHARACTER_UUID,
                    requesterId: self::CLIENT_UUID,
                )
            );

            $this->exceptionThrown = null;
        } catch (Throwable $e) {
            $this->exceptionThrown = $e;
        }
    }

    /**
     * @When /^a stranger tries to read its character$/
     */
    public function aStrangerTriesToReadItsCharacter(): void
    {
        try {
            $this->response = self::container()->queryBus()->ask(
                ViewCharacterQuery::fromScalars(
                    characterId: self::CHARACTER_UUID,
                    requesterId: self::STRANGER_UUID
                )
            );

            $this->exceptionThrown = null;
        } catch (Throwable $e) {
            $this->exceptionThrown = $e;
        }
    }

    /**
     * @When /^a client asks to read all of its characters$/
     */
    public function aClientAsksToReadAllOfItsCharacters(): void
    {
        try {
            $this->response = self::container()->queryBus()->ask(
                ViewAllCharactersQuery::fromScalars(requesterId: self::CLIENT_UUID),
            );

            $this->exceptionThrown = null;
        } catch (Throwable $e) {
            $this->exceptionThrown = $e;
        }
    }

    /**
     * @Then /^the character is registered$/
     */
    public function theCharacterIsRegistered(): void
    {
        Assert::assertNotNull($this->characterId);
        Assert::assertTrue(self::container()->characterGatewaySpy()->has($this->characterId->value()));
    }

    /**
     * @Then /^the character stats are updated as wanted$/
     *
     * @see CharacterContext::itOwnsAWellAdvancedCharacter()
     * @see CharacterContext::aClientDistributesSomeSkillPoints()
     */
    public function theCharacterStatsAreUpdatedAsWanted(): void
    {
        $character = self::container()->characterGatewaySpy()->findOneById(new CharacterId(self::CHARACTER_UUID));
        Assert::assertNotNull($character);

        Assert::assertSame(165 /* 80 + 85 */, $character->characterHealth()->value());
        Assert::assertSame(58  /* 23 + 35 */, $character->characterAttack()->value());
        Assert::assertSame(148 /* 56 + 92 */, $character->characterDefense()->value());
        Assert::assertSame(90  /* 34 + 56 */, $character->characterMagik()->value());
        Assert::assertSame(5, $character->characterSkillPoint()->value());
    }

    /**
     * @Then /^the character is deleted$/
     */
    public function theCharacterIsDeleted(): void
    {
        Assert::assertFalse(self::container()->characterGatewaySpy()->has(self::CHARACTER_UUID));
    }

    /**
     * @Then /^the character count is incremented$/
     */
    public function theCharacterCountIsIncremented(): void
    {
        $ownerId = new CharacterCountOwner(self::CLIENT_UUID);

        Assert::assertTrue(self::container()->characterCountGatewaySpy()->countForOwnerEquals($ownerId, 1));
    }

    /**
     * @Then /^the character count is decremented/
     */
    public function theCharacterCountIsDecremented(): void
    {
        $ownerId = new CharacterCountOwner(self::CLIENT_UUID);

        Assert::assertTrue(self::container()->characterCountGatewaySpy()->countForOwnerEquals($ownerId, 0));
    }

    /**
     * @Then /^the creation was refused$/
     */
    public function theCreationWasRefused(): void
    {
        Assert::assertNotNull($this->exceptionThrown);
        Assert::assertInstanceOf(HasReachedCharacterLimitException::class, $this->exceptionThrown);
    }

    /**
     * @Then /^the stats update was refused$/
     */
    public function theStatsUpdateWasRefused(): void
    {
        Assert::assertNotNull($this->exceptionThrown);
        Assert::assertInstanceOf(NotEnoughSkillPointsException::class, $this->exceptionThrown);
    }

    /**
     * @Then /^the stats update was denied$/
     */
    public function theStatsUpdateWasDenied(): void
    {
        Assert::assertNotNull($this->exceptionThrown);
        Assert::assertInstanceOf(CharacterNotFoundException::class, $this->exceptionThrown);
    }

    /**
     * @Then /^the deletion was refused$/
     */
    public function theDeletionWasRefused(): void
    {
        Assert::assertNotNull($this->exceptionThrown);
        Assert::assertInstanceOf(DeletionIsNotAllowedException::class, $this->exceptionThrown);
    }

    /**
     * @Then /^details about the character were returned$/
     *
     * @throws ReflectionException
     */
    public function detailsAboutTheCharacterWereReturned(): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertInstanceOf(ViewCharacterResponse::class, $this->response);

        /** @var ViewCharacterResponse $response */
        $response = $this->response;

        Assert::assertSame(self::CHARACTER_UUID, ReflectionHelper::propertyValue($response->characterView(), 'id'));
    }

    /**
     * @Then /^the list of all of its characters was returned$/
     */
    public function theListOfAllOfItsCharactersWasReturned(): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertInstanceOf(ViewAllCharactersResponse::class, $this->response);

        /** @var ViewAllCharactersResponse $response */
        $response = $this->response;

        Assert::assertCount(3, $response->viewsList());
    }

    /**
     * @Then /^the query was refused$/
     */
    public function theQueryWasRefused(): void
    {
        Assert::assertNull($this->response);
        Assert::assertNotNull($this->exceptionThrown);
        Assert::assertInstanceOf(CharacterNotFoundException::class, $this->exceptionThrown);
    }

    private function addCharacterToDatabase(Character $character): void
    {
        $this->container()->characterGatewaySpy()->save($character);

        $characterCount = CharacterCount::createForOwner(new CharacterCountOwner(self::CLIENT_UUID));
        $characterCount->incrementOnCharacterCreation();

        $this->container()->characterCountGatewaySpy()->save($characterCount);
    }
}
