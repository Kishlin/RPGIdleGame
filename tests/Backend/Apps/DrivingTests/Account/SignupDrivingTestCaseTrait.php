<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Apps\DrivingTests\Account;

use Kishlin\Backend\Account\Application\Authenticate\AuthenticateCommand;
use Kishlin\Backend\Account\Application\Signup\SignupCommand;
use Kishlin\Backend\Account\Domain\View\AuthenticationDTO;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvokedCount as InvokedCountMatcher;

/**
 * Any client willing to execute the Account/Signup use case should use this trait for its Driving Test.
 *
 * @method MockObject          getMockForAbstractClass(string $class)
 * @method callable            callback(callable $callback)
 * @method InvokedCountMatcher exactly(int $count)
 */
trait SignupDrivingTestCaseTrait
{
    /**
     * Returns a CommandBus mock preconfigured to simulate the behavior of the actual use case.
     * The CommandBus mock will:
     *     - Expect to receive a correct SignupCommand, only one time.
     *     - Return the generated account's uuid.
     *     - Expect to receive a correct AuthenticateCommand, only one time.
     *     - Return the generated authentication details.
     */
    public function configuredCommandBusServiceMock(string $username, string $email, string $password): MockObject
    {
        $bus = $this->getMockForAbstractClass(CommandBus::class);

        $bus
            ->expects($this->exactly(2))
            ->method('execute')
            ->with(
                $this->callback(static function (AuthenticateCommand|SignupCommand $command) use ($username, $email, $password) {
                    return
                        (
                            $command instanceof SignupCommand
                            && $email === $command->email()->value()
                            && $username === $command->username()->value()
                            && password_verify($password, $command->password()->value())
                        ) || (
                            $command instanceof AuthenticateCommand
                            && $username === $command->usernameOrEmail()
                            && $password === $command->password()
                        )
                    ;
                })
            )->willReturnCallback(
                static function (AuthenticateCommand|SignupCommand $command) {
                    if ($command instanceof SignupCommand) {
                        return $command->id();
                    }

                    return AuthenticationDTO::fromScalars('token', 'refreshToken');
                },
            )
        ;

        return $bus;
    }
}
