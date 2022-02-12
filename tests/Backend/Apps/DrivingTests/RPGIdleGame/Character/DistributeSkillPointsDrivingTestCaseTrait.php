<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Apps\DrivingTests\RPGIdleGame\Character;

use Kishlin\Backend\RPGIdleGame\Character\Application\DistributeSkillPoints\DistributeSkillPointsCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvokedCount as InvokedCountMatcher;

/**
 * Any client willing to execute the Character/DistributeSkillPoints use case should use this trait for its Driving Test.
 *
 * @method MockObject          getMockForAbstractClass(string $class)
 * @method callable            callback(callable $callback)
 * @method InvokedCountMatcher once()
 */
trait DistributeSkillPointsDrivingTestCaseTrait
{
    /**
     * Returns a CommandBus mock preconfigured to simulate the behavior of the actual use case.
     * The CommandBus mock will expect to receive a correct DistributeSkillPointsCommand, only one time.
     */
    public function configuredCommandBusServiceMock(
        string $owner,
        string $characterId,
        int $health,
        int $attack,
        int $defense,
        int $magik,
    ): MockObject {
        $bus = $this->getMockForAbstractClass(CommandBus::class);

        $bus
            ->expects($this->once())
            ->method('execute')
            ->with(
                $this->callback(static function (DistributeSkillPointsCommand $parameter) use (
                    $owner,
                    $characterId,
                    $health,
                    $attack,
                    $defense,
                    $magik,
                ) {
                    return
                        $owner === $parameter->requesterId()->value()
                        && $characterId === $parameter->characterId()->value()
                        && $health === $parameter->healthPointsToAdd()
                        && $attack === $parameter->attackPointsToAdd()
                        && $defense === $parameter->defensePointsToAdd()
                        && $magik === $parameter->magikPointsToAdd()
                    ;
                })
            )
        ;

        return $bus;
    }
}
