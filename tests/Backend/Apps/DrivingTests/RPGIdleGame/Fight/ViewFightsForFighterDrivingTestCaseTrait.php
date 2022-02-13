<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Apps\DrivingTests\RPGIdleGame\Fight;

use Kishlin\Backend\RPGIdleGame\Fight\Application\ViewFightsForCharacter\ViewFightsForFighterQuery;
use Kishlin\Backend\RPGIdleGame\Fight\Application\ViewFightsForCharacter\ViewFightsForFighterResponse;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\View\JsonableFightListView;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvokedCount as InvokedCountMatcher;

/**
 * Any client willing to execute the Fight/ViewFightsForFighter use case should use this trait for its Driving Test.
 *
 * @method MockObject          getMockForAbstractClass(string $class)
 * @method callable            callback(callable $callback)
 * @method InvokedCountMatcher once()
 */
trait ViewFightsForFighterDrivingTestCaseTrait
{
    /**
     * Returns a QueryBus mock preconfigured to simulate the behavior of the actual use case.
     * The QueryBus mock will:
     *     - Expect to receive a correct ViewFightsForFighter, only one time.
     *     - Return a placeholder as a view for a list of fights.
     */
    public function configuredQueryBusServiceMock(string $requester, string $fighterId): MockObject
    {
        $bus = $this->getMockForAbstractClass(QueryBus::class);

        $bus
            ->expects($this->once())
            ->method('ask')
            ->with(
                $this->callback(static function (ViewFightsForFighterQuery $parameter) use ($requester, $fighterId) {
                    return
                        $requester === $parameter->requesterId()
                        && $fighterId === $parameter->fighterId()
                    ;
                })
            )
            ->willReturn(
                new ViewFightsForFighterResponse(
                    JsonableFightListView::fromSource([
                        [
                            'id'             => '13266304-bc4b-48c7-b17a-a5647319860f',
                            'winner_id'      => '5ee9d79c-fa2b-45ba-a905-16f20169cc56',
                            'initiator_name' => 'Kishlin',
                            'initiator_rank' => 26,
                            'opponent_name'  => 'Brawler',
                            'opponent_rank'  => 20,
                        ],
                        [
                            'id'             => 'ff52c32a-0ea9-425f-80d9-0f7a1b0aa4cc',
                            'winner_id'      => 'bb9b6931-322d-40c8-8255-54fca7f05e15',
                            'initiator_name' => 'Brawler',
                            'initiator_rank' => 20,
                            'opponent_name'  => 'Kishlin',
                            'opponent_rank'  => 26,
                        ],
                    ]),
                )
            )
        ;

        return $bus;
    }
}
