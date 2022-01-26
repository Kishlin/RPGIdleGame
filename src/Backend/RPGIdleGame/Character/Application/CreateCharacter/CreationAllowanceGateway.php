<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Application\CreateCharacter;

use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;

interface CreationAllowanceGateway
{
    public function isAllowedToCreateACharacter(CharacterOwner $characterOwner): bool;
}
