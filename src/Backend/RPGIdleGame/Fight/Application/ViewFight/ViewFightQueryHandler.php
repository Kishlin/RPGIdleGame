<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Application\ViewFight;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightNotFoundException;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightViewGateway;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryHandler;

final class ViewFightQueryHandler implements QueryHandler
{
    public function __construct(
        private FightViewGateway $fightViewer,
    ) {
    }

    /**
     * @throws FightNotFoundException
     */
    public function __invoke(ViewFightQuery $query): ViewFightResponse
    {
        return new ViewFightResponse(
            $this->fightViewer->viewOneById(
                $query->fightId(),
                $query->requesterId(),
            )
        );
    }
}
