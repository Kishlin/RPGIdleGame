<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Apps\DrivingTests\RPGIdleGame\Fight;

use Kishlin\Backend\RPGIdleGame\Fight\Application\ViewFight\ViewFightQuery;
use Kishlin\Backend\RPGIdleGame\Fight\Application\ViewFight\ViewFightResponse;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\View\JsonableFightView;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvokedCount as InvokedCountMatcher;

/**
 * Any client willing to execute the Fight/ViewOne use case should use this trait for its Driving Test.
 *
 * @method MockObject          getMockForAbstractClass(string $class)
 * @method callable            callback(callable $callback)
 * @method InvokedCountMatcher once()
 */
trait ViewFightDrivingTestCaseTrait
{
    /**
     * Returns a QueryBus mock preconfigured to simulate the behavior of the actual use case.
     * The QueryBus mock will:
     *     - Expect to receive a correct ViewFightQuery, only one time.
     *     - Return a placeholder as a view for the asked fight.
     */
    public function configuredQueryBusServiceMock(string $requester, string $fightId): MockObject
    {
        $bus = $this->getMockForAbstractClass(QueryBus::class);

        $bus
            ->expects($this->once())
            ->method('ask')
            ->with(
                $this->callback(static function (ViewFightQuery $parameter) use ($requester, $fightId) {
                    return
                        $requester === $parameter->requesterId()
                        && $fightId === $parameter->fightId()
                    ;
                })
            )
            ->willReturn(
                new ViewFightResponse(
                    JsonableFightView::fromSource([
                        'id'        => $fightId,
                        'initiator' => [
                            'account_username' => 'Stranger',
                            'character_name'   => 'Fighter',
                            'character_id'     => '23829de0-dd2e-4de4-921c-272a2ab206b5',
                            'health'           => 30,
                            'attack'           => 25,
                            'defense'          => 5,
                            'magik'            => 10,
                            'rank'             => 31,
                        ],
                        'opponent' => [
                            'account_username' => 'User',
                            'character_name'   => 'Kishlin',
                            'character_id'     => '08a1c683-9a8f-4ce5-8ebb-e5ace0f0df2f',
                            'health'           => 25,
                            'attack'           => 18,
                            'defense'          => 12,
                            'magik'            => 10,
                            'rank'             => 26,
                        ],
                        'turns'      => [],
                        'winner_id'  => null,
                        'fight_date' => 753926409,
                    ]),
                )
            )
        ;

        return $bus;
    }
}
