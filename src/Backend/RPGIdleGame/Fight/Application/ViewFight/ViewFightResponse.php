<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Application\ViewFight;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\View\SerializableFightView;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class ViewFightResponse implements Response
{
    public function __construct(
        private SerializableFightView $fightView
    ) {
    }

    public function fightView(): SerializableFightView
    {
        return $this->fightView;
    }
}
