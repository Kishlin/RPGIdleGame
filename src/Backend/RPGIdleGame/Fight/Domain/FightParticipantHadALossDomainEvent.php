<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Domain;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightId;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantId;
use Kishlin\Backend\Shared\Domain\Bus\Event\DomainEvent;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class FightParticipantHadALossDomainEvent extends DomainEvent
{
    public function __construct(
        private FightId $fightId,
        private FightParticipantId $fightParticipantId,
    ) {
        parent::__construct($this->fightId);
    }

    public static function eventName(): string
    {
        return 'fight.result.loss';
    }

    public function fightId(): UuidValueObject
    {
        return $this->fightId;
    }

    public function fightParticipant(): UuidValueObject
    {
        return $this->fightParticipantId;
    }
}
