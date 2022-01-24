<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Domain;

use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;
use Kishlin\Backend\Shared\Domain\Bus\Event\DomainEvent;

final class CharacterCreatedDomainEvent extends DomainEvent
{
    public function __construct(
        CharacterId $characterId,
        private CharacterOwner $characterOwner,
    ) {
        parent::__construct($characterId);
    }

    public static function eventName(): string
    {
        return 'account.created';
    }

    public function characterOwner(): CharacterOwner
    {
        return $this->characterOwner;
    }
}
