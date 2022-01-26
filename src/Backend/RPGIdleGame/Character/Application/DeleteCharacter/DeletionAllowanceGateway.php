<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Application\DeleteCharacter;

use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;

interface DeletionAllowanceGateway
{
    public function requesterIsTheRightfulOwner(CharacterOwner $deletionRequester, CharacterId $characterId): bool;
}
