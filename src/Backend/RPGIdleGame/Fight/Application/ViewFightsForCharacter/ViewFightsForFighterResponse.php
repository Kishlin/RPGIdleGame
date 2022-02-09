<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Application\ViewFightsForCharacter;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\View\JsonableFightListItem;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class ViewFightsForFighterResponse implements Response
{
    /**
     * @param JsonableFightListItem[] $fightViews
     */
    public function __construct(
        private array $fightViews
    ) {
    }

    /**
     * @return JsonableFightListItem[]
     */
    public function fightViews(): array
    {
        return $this->fightViews;
    }
}
