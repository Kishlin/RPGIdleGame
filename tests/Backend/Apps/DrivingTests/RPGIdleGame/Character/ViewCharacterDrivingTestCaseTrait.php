<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Apps\DrivingTests\RPGIdleGame\Character;

use Kishlin\Backend\RPGIdleGame\Character\Application\ViewCharacter\ViewCharacterQuery;
use Kishlin\Backend\RPGIdleGame\Character\Application\ViewCharacter\ViewCharacterResponse;
use Kishlin\Backend\RPGIdleGame\Character\Domain\View\JsonableCharacterView;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvokedCount as InvokedCountMatcher;

/**
 * Any client willing to execute the Character/View use case should use this trait for its Driving Test.
 *
 * @method MockObject          getMockForAbstractClass(string $class)
 * @method callable            callback(callable $callback)
 * @method InvokedCountMatcher once()
 */
trait ViewCharacterDrivingTestCaseTrait
{
    /**
     * Returns a QueryBus mock preconfigured to simulate the behavior of the actual use case.
     * The QueryBus mock will
     *     - expect to receive a correct ViewCharacterQuery, only one time.
     *     - return a dummy Jsonable Character View to simulate the query handler behavior.
     */
    public function configuredQueryBusServiceMock(string $owner, string $characterId): MockObject
    {
        $bus = $this->getMockForAbstractClass(QueryBus::class);

        $bus
            ->expects($this->once())
            ->method('ask')
            ->with(
                $this->callback(static function (ViewCharacterQuery $parameter) use ($owner, $characterId) {
                    return
                        $owner === $parameter->requesterId()
                        && $characterId === $parameter->characterId()
                    ;
                })
            )
            ->willReturn(
                new ViewCharacterResponse(
                    JsonableCharacterView::fromSource([
                        'id'           => $characterId,
                        'name'         => 'Kishlin',
                        'owner'        => $owner,
                        'skill_points' => 12,
                        'health'       => 10,
                        'attack'       => 0,
                        'defense'      => 0,
                        'magik'        => 0,
                        'rank'         => 0,
                        'fights_count' => 0,
                        'wins_count'   => 0,
                        'draws_count'  => 0,
                        'losses_count' => 0,
                    ]),
                )
            )
        ;

        return $bus;
    }
}
