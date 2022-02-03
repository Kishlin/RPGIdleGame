<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine\Constraint;

use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\AbstractFightParticipant;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantId;
use PHPUnit\Framework\Constraint\Constraint;

final class FightParticipantRepresentsTheCharacterConstraint extends Constraint
{
    public function __construct(
        private Character $character
    ) {
    }

    public function toString(): string
    {
        return 'represents the character of id ' . $this->character->id()->value();
    }

    /**
     * @noinspection PhpParameterNameChangedDuringInheritanceInspection
     *
     * @param AbstractFightParticipant $participant
     *
     * {@inheritDoc}
     */
    protected function matches($participant): bool
    {
        return $participant instanceof AbstractFightParticipant
            && $participant->id() instanceof FightParticipantId
            && $participant->characterId()->equals($this->character->id())
            && $participant->health()->equals($this->character->health())
            && $participant->attack()->equals($this->character->attack())
            && $participant->magik()->equals($this->character->magik())
            && $participant->defense()->equals($this->character->defense())
        ;
    }
}
