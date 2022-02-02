<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Application\InitiateAFight;

interface FightInitiationAllowanceGateway
{
    public function requesterIsAllowedToFightWithFighter(FightRequesterId $requesterId, FighterId $fighterId): bool;
}
