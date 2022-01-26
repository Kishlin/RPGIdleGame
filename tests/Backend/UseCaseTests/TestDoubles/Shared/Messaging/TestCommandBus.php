<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Messaging;

use Kishlin\Backend\Account\Application\Signup\SignupCommand;
use Kishlin\Backend\RPGIdleGame\Character\Application\CreateCharacter\CreateCharacterCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Tests\Backend\UseCaseTests\TestServiceContainer;

final class TestCommandBus implements CommandBus
{
    public function __construct(
        private TestServiceContainer $testServiceContainer
    ) {
    }

    public function execute(Command $command): mixed
    {
        if ($command instanceof SignupCommand) {
            return $this->testServiceContainer->signupCommandHandler()($command);
        }

        if ($command instanceof CreateCharacterCommand) {
            return $this->testServiceContainer->createCharacterHandler()($command);
        }

        throw new \RuntimeException('Unknown command type: ' . get_class($command));
    }
}
