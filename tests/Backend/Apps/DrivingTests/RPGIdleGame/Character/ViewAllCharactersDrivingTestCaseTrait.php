<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Apps\DrivingTests\RPGIdleGame\Character;

use Kishlin\Backend\RPGIdleGame\Character\Application\ViewAllCharacter\ViewAllCharactersQuery;
use Kishlin\Backend\RPGIdleGame\Character\Application\ViewAllCharacter\ViewAllCharactersResponse;
use Kishlin\Backend\RPGIdleGame\Character\Domain\View\JsonableCharactersListView;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvokedCount as InvokedCountMatcher;

/**
 * Any client willing to execute the Character/ViewAll use case should use this trait for its Driving Test.
 *
 * @method MockObject          getMockForAbstractClass(string $class)
 * @method callable            callback(callable $callback)
 * @method InvokedCountMatcher once()
 */
trait ViewAllCharactersDrivingTestCaseTrait
{
    /**
     * Returns a QueryBus mock preconfigured to simulate the behavior of the actual use case.
     * The QueryBus mock will
     *     - expect to receive a correct ViewAllCharactersQuery, only one time.
     *     - return a dummy Jsonable Character View to simulate the query handler behavior.
     */
    public function configuredQueryBusServiceMock(string $owner): MockObject
    {
        $bus = $this->getMockForAbstractClass(QueryBus::class);

        $bus
            ->expects($this->once())
            ->method('ask')
            ->with(
                $this->callback(static function (ViewAllCharactersQuery $parameter) use ($owner) {
                    return $owner === $parameter->requesterId();
                })
            )
            ->willReturn(
                new ViewAllCharactersResponse(JsonableCharactersListView::fromSource([])),
            )
        ;

        return $bus;
    }
}
