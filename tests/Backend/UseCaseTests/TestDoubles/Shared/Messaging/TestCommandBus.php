<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Messaging;

use Kishlin\Backend\Account\Application\Authenticate\AuthenticateCommand;
use Kishlin\Backend\Account\Application\RefreshAuthentication\RefreshAuthenticationCommand;
use Kishlin\Backend\Account\Application\Signup\SignupCommand;
use Kishlin\Backend\RPGIdleGame\Character\Application\CreateCharacter\CreateCharacterCommand;
use Kishlin\Backend\RPGIdleGame\Character\Application\DeleteCharacter\DeleteCharacterCommand;
use Kishlin\Backend\RPGIdleGame\Character\Application\DistributeSkillPoints\DistributeSkillPointsCommand;
use Kishlin\Backend\RPGIdleGame\Fight\Application\InitiateAFight\InitiateAFightCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Tests\Backend\UseCaseTests\TestServiceContainer;
use RuntimeException;

final class TestCommandBus implements CommandBus
{
    public function __construct(
        private TestServiceContainer $testServiceContainer
    ) {
    }

    /** @noinspection PhpMixedReturnTypeCanBeReducedInspection */
    public function execute(Command $command): mixed
    {
        if ($command instanceof SignupCommand) {
            return $this->testServiceContainer->signupCommandHandler()($command);
        }

        if ($command instanceof AuthenticateCommand) {
            return $this->testServiceContainer->authenticateCommandHandler()($command);
        }

        if ($command instanceof RefreshAuthenticationCommand) {
            return $this->testServiceContainer->refreshAuthenticationCommandHandler()($command);
        }

        if ($command instanceof DistributeSkillPointsCommand) {
            $this->testServiceContainer->distributeSkillPointsHandler()($command);

            return null;
        }

        if ($command instanceof CreateCharacterCommand) {
            return $this->testServiceContainer->createCharacterHandler()($command);
        }

        if ($command instanceof DeleteCharacterCommand) {
            $this->testServiceContainer->deleteCharacterHandler()($command);

            return null;
        }

        if ($command instanceof InitiateAFightCommand) {
            return $this->testServiceContainer->initiateAFightCommandHandler()($command);
        }

        throw new RuntimeException('Unknown command type: ' . get_class($command));
    }
}
