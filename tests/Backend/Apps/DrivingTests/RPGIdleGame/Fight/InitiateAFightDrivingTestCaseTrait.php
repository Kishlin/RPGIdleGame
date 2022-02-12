<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Apps\DrivingTests\RPGIdleGame\Fight;

use Kishlin\Backend\RPGIdleGame\Fight\Application\InitiateAFight\InitiateAFightCommand;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvokedCount as InvokedCountMatcher;

/**
 * Any client willing to execute the Fight/Initiate use case should use this trait for its Driving Test.
 *
 * @method MockObject          getMockForAbstractClass(string $class)
 * @method callable            callback(callable $callback)
 * @method InvokedCountMatcher once()
 */
trait InitiateAFightDrivingTestCaseTrait
{
    /**
     * Returns a CommandBus mock preconfigured to simulate the behavior of the actual use case.
     * The CommandBus mock will:
     *     - Expect to receive a correct InitiateAFightCommand, only one time.
     *     - Return the fightId as the initiated fight's id.
     */
    public function configuredCommandBusServiceMock(string $owner, string $fighterId, string $fightId): MockObject
    {
        $bus = $this->getMockForAbstractClass(CommandBus::class);

        $bus
            ->expects($this->once())
            ->method('execute')
            ->with(
                $this->callback(static function (InitiateAFightCommand $parameter) use ($owner, $fighterId) {
                    return
                        $owner === $parameter->requesterId()->value()
                        && $fighterId === $parameter->fighterId()->value()
                    ;
                })
            )
            ->willReturn(new FightId($fightId))
        ;

        return $bus;
    }
}
