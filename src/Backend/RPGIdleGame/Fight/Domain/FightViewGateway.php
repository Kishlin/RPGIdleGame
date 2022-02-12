<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Domain;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\View\JsonableFightListView;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\View\JsonableFightView;

interface FightViewGateway
{
    /**
     * @throws FightNotFoundException
     */
    public function viewOneById(string $fightId, string $requesterId): JsonableFightView;

    /**
     *@throws CannotAccessFightsException
     */
    public function viewAllForFighter(string $fighterId, string $requesterId): JsonableFightListView;
}
