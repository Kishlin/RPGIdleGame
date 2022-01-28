<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Application\ViewCharacter;

use Kishlin\Backend\RPGIdleGame\Character\Domain\View\SerializableCharacterView;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class ViewCharacterResponse implements Response
{
    public function __construct(
        private SerializableCharacterView $characterView
    ) {
    }

    public function characterView(): SerializableCharacterView
    {
        return $this->characterView;
    }
}
