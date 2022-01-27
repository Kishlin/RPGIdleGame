<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Application\ViewCharacter;

use Kishlin\Backend\RPGIdleGame\Character\Application\DistributeSkillPoints\CharacterNotFoundException;
use Kishlin\Backend\RPGIdleGame\Character\Domain\CharacterViewGateway;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryHandler;

final class ViewCharacterQueryHandler implements QueryHandler
{
    public function __construct(
        private CharacterViewGateway $characterViewer,
    ) {
    }

    /**
     * @throws CharacterNotFoundException
     */
    public function __invoke(ViewCharacterQuery $query): ViewCharacterResponse
    {
        return new ViewCharacterResponse(
            $this->characterViewer->viewOneById(
                $query->characterId()
            )
        );
    }
}
