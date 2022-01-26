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
use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterName;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCount;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountOwner;
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
        $this->container()->characterGatewaySpy()->save(Character::createFresh(
            new CharacterId(self::CHARACTER_UUID),
            new CharacterName('Kishlin'),
            new CharacterOwner(self::CLIENT_UUID),
        ));

        $characterCount = CharacterCount::createForOwner(new CharacterCountOwner(self::CLIENT_UUID));
        $characterCount->incrementOnCharacterCreation();

        $this->container()->characterCountGatewaySpy()->save($characterCount);
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
     * @Then /^the character is registered$/
     */
    public function theCharacterIsRegistered(): void
    {
        Assert::assertNotNull($this->characterId);
        Assert::assertTrue(self::container()->characterGatewaySpy()->has($this->characterId));
    }

    /**
     * @Then /^the character is deleted$/
     */
    public function theCharacterIsDeleted(): void
    {
        Assert::assertFalse(self::container()->characterGatewaySpy()->has(new CharacterId(self::CHARACTER_UUID)));
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
     * @Then /^the deletion was refused$/
     */
    public function theDeletionWasRefused(): void
    {
        Assert::assertNotNull($this->exceptionThrown);
        Assert::assertInstanceOf(DeletionIsNotAllowedException::class, $this->exceptionThrown);
    }
}
