<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Application\ViewFight;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\View\JsonableFightView;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class ViewFightResponse implements Response
{
    public function __construct(
        private JsonableFightView $fightView
    ) {
    }

    public function fightView(): JsonableFightView
    {
        return $this->fightView;
    }
}
