<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Application\ViewFightsForCharacter;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\CannotAccessFightsException;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightViewGateway;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryHandler;

final class ViewFightsForFighterQueryHandler implements QueryHandler
{
    public function __construct(
        private FightViewGateway $fightViewer,
    ) {
    }

    /**
     * @throws CannotAccessFightsException
     */
    public function __invoke(ViewFightsForFighterQuery $query): ViewFightsForFighterResponse
    {
        return new ViewFightsForFighterResponse(
            $this->fightViewer->viewAllForFighter(
                $query->fighterId(),
                $query->requesterId(),
            ),
        );
    }
}
