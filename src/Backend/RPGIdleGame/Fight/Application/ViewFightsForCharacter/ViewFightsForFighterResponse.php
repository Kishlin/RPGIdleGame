<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Application\ViewFightsForCharacter;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\View\SerializableFightListItem;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class ViewFightsForFighterResponse implements Response
{
    /**
     * @param SerializableFightListItem[] $fightView
     */
    public function __construct(
        private array $fightView
    ) {
    }

    /**
     * @return SerializableFightListItem[]
     */
    public function fightView(): array
    {
        return $this->fightView;
    }
}
