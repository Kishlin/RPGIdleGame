<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Apps\DrivingTests\RPGIdleGame\Character;

use Kishlin\Backend\RPGIdleGame\Character\Application\CreateCharacter\CreateCharacterCommand;
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
trait CreateCharacterDrivingTestCaseTrait
{
    /**
     * Returns a CommandBus mock preconfigured to simulate the behavior of the actual use case.
     * The CommandBus mock will:
     *     - Expect to receive a correct CreateCharacter, only one time.
     *     - Return the generated character's uuid.
     */
    public function configuredCommandBusServiceMock(string $owner, string $name): MockObject
    {
        $bus = $this->getMockForAbstractClass(CommandBus::class);

        $bus->expects($this->once())->method('execute')->with(
            $this->callback(static function (CreateCharacterCommand $parameter) use ($owner, $name) {
                return
                    $owner === $parameter->characterOwner()->value()
                    && $name === $parameter->characterName()->value()
                ;
            })
        )->willReturnCallback(
            static fn (CreateCharacterCommand $command) => $command->characterId(),
        );

        return $bus;
    }
}
