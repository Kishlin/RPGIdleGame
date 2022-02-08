<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Domain;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightId;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantCharacterId;
use Kishlin\Backend\Shared\Domain\Bus\Event\DomainEvent;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class FightParticipantHadADrawDomainEvent extends DomainEvent
{
    public function __construct(
        private FightId $fightId,
        private FightParticipantCharacterId $characterId,
    ) {
        parent::__construct($this->fightId);
    }

    public function fightId(): UuidValueObject
    {
        return $this->fightId;
    }

    public function characterId(): UuidValueObject
    {
        return $this->characterId;
    }
}
