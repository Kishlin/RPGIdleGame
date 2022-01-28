<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Application\ViewAllCharacter;

use Kishlin\Backend\RPGIdleGame\Character\Domain\CharacterViewGateway;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryHandler;

final class ViewAllCharactersQueryHandler implements QueryHandler
{
    public function __construct(
        private CharacterViewGateway $characterViewGateway,
    ) {
    }

    public function __invoke(ViewAllCharactersQuery $query): ViewAllCharactersResponse
    {
        return new ViewAllCharactersResponse(
            $this->characterViewGateway->viewAllForOwner(
                $query->requesterId()
            )
        );
    }
}
