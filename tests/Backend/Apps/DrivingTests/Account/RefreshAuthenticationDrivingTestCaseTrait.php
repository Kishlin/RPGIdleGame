<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Apps\DrivingTests\Account;

use Kishlin\Backend\Account\Application\RefreshAuthentication\RefreshAuthenticationCommand;
use Kishlin\Backend\Account\Domain\View\JsonableSimpleAuthentication;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvokedCount as InvokedCountMatcher;

/**
 * Any client willing to execute the Account/RefreshAuthentication use case should use this trait for its Driving Test.
 *
 * @method MockObject          getMockForAbstractClass(string $class)
 * @method callable            callback(callable $callback)
 * @method InvokedCountMatcher once()
 */
trait RefreshAuthenticationDrivingTestCaseTrait
{
    /**
     * Returns a CommandBus mock preconfigured to simulate the behavior of the actual use case.
     * The CommandBus mock will:
     *     - Expect to receive a correct RefreshAuthenticationCommand, only one time.
     *     - Return the simple authentication data.
     */
    public function configuredCommandBusServiceMock(string $refreshToken): MockObject
    {
        $bus = $this->getMockForAbstractClass(CommandBus::class);

        $bus->expects($this->once())->method('execute')->with(
            $this->callback(static function (RefreshAuthenticationCommand $parameter) use ($refreshToken) {
                return $refreshToken === $parameter->refreshToken();
            })
        )->willReturnCallback(
            static fn (RefreshAuthenticationCommand $command) => JsonableSimpleAuthentication::fromScalars('token'),
        );

        return $bus;
    }
}
