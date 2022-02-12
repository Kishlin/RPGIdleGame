<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Apps\DrivingTests\RPGIdleGame\Character;

use Kishlin\Backend\RPGIdleGame\Character\Application\DeleteCharacter\DeleteCharacterCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvokedCount as InvokedCountMatcher;

/**
 * Any client willing to execute the Character/Delete use case should use this trait for its Driving Test.
 *
 * @method MockObject          getMockForAbstractClass(string $class)
 * @method callable            callback(callable $callback)
 * @method InvokedCountMatcher once()
 */
trait DeleteCharacterDrivingTestCaseTrait
{
    /**
     * Returns a CommandBus mock preconfigured to simulate the behavior of the actual use case.
     * The CommandBus mock will expect to receive a correct DeleteCharacter, only one time.
     */
    public function configuredCommandBusServiceMock(string $owner, string $characterId): MockObject
    {
        $bus = $this->getMockForAbstractClass(CommandBus::class);

        $bus
            ->expects($this->once())
            ->method('execute')
            ->with(
                $this->callback(static function (DeleteCharacterCommand $parameter) use ($owner, $characterId) {
                    return
                        $owner === $parameter->accountRequestingDeletion()->value()
                        && $characterId === $parameter->characterId()->value()
                    ;
                })
            )
        ;

        return $bus;
    }
}
