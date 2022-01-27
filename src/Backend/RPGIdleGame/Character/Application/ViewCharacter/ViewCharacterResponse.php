<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Application\ViewCharacter;

use Kishlin\Backend\RPGIdleGame\Character\Domain\View\CompleteCharacterView;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class ViewCharacterResponse implements Response
{
    public function __construct(
        private CompleteCharacterView $characterView
    ) {
    }

    public function characterView(): CompleteCharacterView
    {
        return $this->characterView;
    }
}
