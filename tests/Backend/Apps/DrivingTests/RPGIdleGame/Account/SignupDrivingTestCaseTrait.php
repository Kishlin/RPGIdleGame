<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Apps\DrivingTests\RPGIdleGame\Account;

use Kishlin\Backend\Account\Application\Signup\SignupCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;

/**
 * Any client willing to execute the Account/Signup use case should use this trait for its Driving Test.
 */
trait SignupDrivingTestCaseTrait
{
    /**
     * Returns a CommandBus mock preconfigured to simulate the behavior of the actual use case.
     * The CommandBus mock will:
     *     - Expect to receive a correct SignupCommand, only one time.
     *     - Return the generated account's uuid.
     */
    public function configuredCommandBusServiceMock(string $username, string $email, string $password): CommandBus
    {
        $bus = $this->getMockForAbstractClass(CommandBus::class);

        $bus->expects($this->once())->method('execute')->with(
            $this->callback(static function (SignupCommand $parameter) use ($username, $email, $password) {
                return
                    $username === $parameter->username()->value()
                    && $email === $parameter->email()->value()
                    && password_verify($password, $parameter->password()->value())
                ;
            })
        )->willReturnCallback(
            static fn (SignupCommand $command) => $command->id(),
        );

        return $bus;
    }
}