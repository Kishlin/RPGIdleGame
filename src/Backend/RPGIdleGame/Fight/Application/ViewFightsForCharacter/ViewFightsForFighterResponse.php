<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Application\ViewFightsForCharacter;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\View\JsonableFightListView;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class ViewFightsForFighterResponse implements Response
{
    public function __construct(
        private JsonableFightListView $fightsView
    ) {
    }

    public function fights(): JsonableFightListView
    {
        return $this->fightsView;
    }
}
