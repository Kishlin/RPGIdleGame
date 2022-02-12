<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Application\ViewAllCharacter;

use Kishlin\Backend\RPGIdleGame\Character\Domain\View\JsonableCharactersListView;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class ViewAllCharactersResponse implements Response
{
    public function __construct(
        private JsonableCharactersListView $viewsList,
    ) {
    }

    public function viewsList(): JsonableCharactersListView
    {
        return $this->viewsList;
    }
}
