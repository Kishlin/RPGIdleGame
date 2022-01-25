<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context;

use Kishlin\Backend\Account\Domain\Account;
use Kishlin\Backend\Account\Domain\AccountEmail;
use Kishlin\Backend\Account\Domain\AccountId;
use Kishlin\Backend\Account\Domain\AccountPassword;
use Kishlin\Backend\Account\Domain\AccountUsername;
use Kishlin\Backend\RPGIdleGame\Character\Application\CreateCharacter\CreateCharacterCommand;
use Kishlin\Backend\RPGIdleGame\Character\Application\CreateCharacter\HasReachedCharacterLimitException;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCount;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountOwner;
use PHPUnit\Framework\Assert;
use ReflectionException;
use Throwable;

trait CharacterTrait
{
    private ?CharacterId $characterId   = null;
    private ?Throwable $exceptionThrown = null;

    /**
     * @Given /^a client has an account$/
     *
     * @throws ReflectionException
     */
    public function aClientHasAnAccount()
    {
        $this->container->accountGatewaySpy()->save(Account::createActiveAccount(
            new AccountId($this->accountUuid()),
            new AccountUsername('User'),
            new AccountPassword('password'),
            new AccountEmail(self::emailToUse()),
        ));

        $this->container->characterCountGatewaySpy()->save(CharacterCount::createForOwner(
            new CharacterCountOwner($this->accountUuid()),
        ));
    }

    /**
     * @Given /^it has reached the character limit$/
     *
     * @throws ReflectionException
     */
    public function itHasReachedTheCharacterLimit()
    {
        $this
            ->container
            ->characterCountGatewaySpy()
            ->manuallyOverrideCountForOwner(new AccountId($this->accountUuid()), CharacterCount::CHARACTER_LIMIT)
        ;
    }

    /**
     * @When /^a client creates a character$/
     */
    public function aClientCreatesACharacter()
    {
        try {
            $response = $this->container->commandBus()->execute(
                CreateCharacterCommand::fromScalars(
                    characterId: $this->characterUuid(),
                    characterName: 'Kishlin',
                    ownerUuid: $this->accountUuid(),
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
     * @Then /^the character is registered$/
     */
    public function theCharacterIsRegistered()
    {
        Assert::assertNotNull($this->characterId);
        Assert::assertTrue($this->container->characterGatewaySpy()->has($this->characterId));
    }

    /**
     * @Then /^the character count is incremented$/
     *
     * @throws ReflectionException
     */
    public function theCharacterCountIsIncrementedAccordingly()
    {
        $ownerId = new CharacterCountOwner($this->accountUuid());

        Assert::assertTrue($this->container->characterCountGatewaySpy()->countForOwnerEquals($ownerId, 1));
    }

    /**
     * @Then /^the creation was refused$/
     */
    public function theCreationWasRefused()
    {
        Assert::assertNotNull($this->exceptionThrown);
        Assert::assertInstanceOf(HasReachedCharacterLimitException::class, $this->exceptionThrown);
    }

    private function accountUuid(): string
    {
        return '97c116cc-21b0-4624-8e02-88b9b1a977a7';
    }

    private function characterUuid(): string
    {
        return 'fa2e098a-1ed4-4ddb-91d1-961e0af7143b';
    }
}
