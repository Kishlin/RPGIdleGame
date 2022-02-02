<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Domain;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\View\SerializableFightView;

interface FightViewGateway
{
    /**
     * @throws FightNotFoundException
     */
    public function viewOneById(string $fightId, string $requesterId): SerializableFightView;
}
