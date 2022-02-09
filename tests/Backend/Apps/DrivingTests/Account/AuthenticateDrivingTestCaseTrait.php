<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Apps\DrivingTests\Account;

use Kishlin\Backend\Account\Application\Authenticate\AuthenticateCommand;
use Kishlin\Backend\Account\Domain\View\JsonableAuthentication;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvokedCount as InvokedCountMatcher;

/**
 * Any client willing to execute the Account/Signup use case should use this trait for its Driving Test.
 *
 * @method MockObject          getMockForAbstractClass(string $class)
 * @method callable            callback(callable $callback)
 * @method InvokedCountMatcher once()
 */
trait AuthenticateDrivingTestCaseTrait
{
    /**
     * Returns a CommandBus mock preconfigured to simulate the behavior of the actual use case.
     * The CommandBus mock will:
     *     - Expect to receive a correct AuthenticateCommand, only one time.
     *     - Return the authentication data.
     */
    public function configuredCommandBusServiceMock(string $login, string $password): MockObject
    {
        $bus = $this->getMockForAbstractClass(CommandBus::class);

        $bus->expects($this->once())->method('execute')->with(
            $this->callback(static function (AuthenticateCommand $parameter) use ($login, $password) {
                return
                    $login === $parameter->usernameOrEmail()
                    && $password === $parameter->password()
                ;
            })
        )->willReturnCallback(
            static fn (AuthenticateCommand $command) => JsonableAuthentication::fromScalars('token', 'refreshToken'),
        );

        return $bus;
    }
}
