<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Domain;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\View\JsonableFightListItem;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\View\JsonableFightView;

interface FightViewGateway
{
    /**
     * @throws FightNotFoundException
     */
    public function viewOneById(string $fightId, string $requesterId): JsonableFightView;

    /**
     *@throws CannotAccessFightsException
     *
     * @return JsonableFightListItem[]
     */
    public function viewAllForFighter(string $fighterId, string $requesterId): array;
}
