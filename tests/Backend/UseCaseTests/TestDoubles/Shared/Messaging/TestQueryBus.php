<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Messaging;

use Kishlin\Backend\RPGIdleGame\Character\Application\ViewAllCharacter\ViewAllCharactersQuery;
use Kishlin\Backend\RPGIdleGame\Character\Application\ViewCharacter\ViewCharacterQuery;
use Kishlin\Backend\RPGIdleGame\Fight\Application\ViewFight\ViewFightQuery;
use Kishlin\Backend\RPGIdleGame\Fight\Application\ViewFightsForCharacter\ViewFightsForFighterQuery;
use Kishlin\Backend\Shared\Domain\Bus\Query\Query;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;
use Kishlin\Tests\Backend\UseCaseTests\TestServiceContainer;
use RuntimeException;

final class TestQueryBus implements QueryBus
{
    public function __construct(
        private TestServiceContainer $testServiceContainer,
    ) {
    }

    /**
     * @throws RuntimeException
     */
    public function ask(Query $query): Response
    {
        if ($query instanceof ViewCharacterQuery) {
            return $this->testServiceContainer->viewCharacterQueryHandler()($query);
        }

        if ($query instanceof ViewAllCharactersQuery) {
            return $this->testServiceContainer->viewAllCharactersQueryHandler()($query);
        }

        if ($query instanceof ViewFightQuery) {
            return $this->testServiceContainer->viewFightQueryHandler()($query);
        }

        if ($query instanceof ViewFightsForFighterQuery) {
            return $this->testServiceContainer->fightsForFighterQueryHandler()($query);
        }

        throw new RuntimeException('Unknown query type: ' . get_class($query));
    }
}
