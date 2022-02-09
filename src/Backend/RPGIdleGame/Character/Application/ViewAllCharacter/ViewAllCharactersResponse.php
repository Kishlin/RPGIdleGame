<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Application\ViewAllCharacter;

use Kishlin\Backend\RPGIdleGame\Character\Domain\View\JsonableCharacterView;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class ViewAllCharactersResponse implements Response
{
    /**
     * @param JsonableCharacterView[] $viewsList
     */
    public function __construct(
        private array $viewsList,
    ) {
    }

    /**
     * @return JsonableCharacterView[]
     */
    public function viewsList(): array
    {
        return $this->viewsList;
    }
}
