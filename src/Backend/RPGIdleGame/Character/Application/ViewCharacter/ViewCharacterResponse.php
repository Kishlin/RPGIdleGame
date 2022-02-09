<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Application\ViewCharacter;

use Kishlin\Backend\RPGIdleGame\Character\Domain\View\JsonableCharacterView;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class ViewCharacterResponse implements Response
{
    public function __construct(
        private JsonableCharacterView $characterView
    ) {
    }

    public function characterView(): JsonableCharacterView
    {
        return $this->characterView;
    }
}
