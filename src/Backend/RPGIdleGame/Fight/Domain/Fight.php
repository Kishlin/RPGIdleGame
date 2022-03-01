<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Domain;

use ArrayIterator;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightDate;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightId;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightTurnId;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightTurnIndex;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightWinnerId;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;

final class Fight extends AggregateRoot
{
    /** @var iterable<FightTurn> */
    private iterable $turns = [];

    private FightWinnerId $winnerId;

    private function __construct(
        private FightId $id,
        private FightInitiator $initiator,
        private FightOpponent $opponent,
        private FightDate $date,
    ) {
        $this->winnerId = new FightWinnerId(null);
    }

    public static function initiate(
        FightId $id,
        FightInitiator $initiator,
        FightOpponent $opponent,
        FightDate $date,
    ): self {
        return new self($id, $initiator, $opponent, $date);
    }

    /**
     * Computes the fight through completion.
     * Self::winnerId will be filled if there is a winner, and domain events will be recorded.
     * If you need to get the fight result for other purposes, use a DTO.
     */
    public function unfold(Dice $dice, UuidGenerator $uuidGenerator): void
    {
        $initiatorMightWin = $this->initiatorCanDefeatOpponent();
        $opponentMightWin  = $this->opponentCanDefeatInitiator();

        if (false === $initiatorMightWin && false === $opponentMightWin) {
            $this->onFightWillAlwaysBeADraw();
        } elseif (false === $opponentMightWin) {
            $this->onFightEndsWithAClearWinner(winner: $this->initiator, loser: $this->opponent);
        } elseif (false === $initiatorMightWin) {
            $this->onFightEndsWithAClearWinner(winner: $this->opponent, loser: $this->initiator);
        } else {
            // Modify only copies of fight participants, so we don't save modified data through the gateways.
            $defender = $this->opponent->clone();
            $attacker = $this->initiator->clone();

            $turnIndex     = new FightTurnIndex(0);
            $turnsIterator = new ArrayIterator([]);

            $this->computeTurnsUntilResult($attacker, $defender, $dice, $uuidGenerator, $turnIndex, $turnsIterator);
        }
    }

    public function id(): FightId
    {
        return $this->id;
    }

    public function initiator(): FightInitiator
    {
        return $this->initiator;
    }

    public function opponent(): FightOpponent
    {
        return $this->opponent;
    }

    public function winnerId(): FightWinnerId
    {
        return $this->winnerId;
    }

    public function date(): FightDate
    {
        return $this->date;
    }

    /**
     * @return iterable<FightTurn>
     */
    public function turns(): iterable
    {
        return $this->turns;
    }

    /**
     * The initiator cannot win if it cannot deal damages to the opponent.
     */
    private function initiatorCanDefeatOpponent(): bool
    {
        return $this->opponent->defense()->value() < $this->maxPossibleDamages($this->initiator);
    }

    /**
     * The opponent cannot win if it cannot deal damages to the initiator.
     */
    private function opponentCanDefeatInitiator(): bool
    {
        return $this->initiator->defense()->value() < $this->maxPossibleDamages($this->opponent);
    }

    private function maxPossibleDamages(AbstractFightParticipant $attacker): int
    {
        return $attacker->attack()->value() >= $attacker->magik()->value() ?
            max($attacker->attack()->value(), 2 * $attacker->magik()->value()) :
            $attacker->attack()->value()
        ;
    }

    private function onFightWillAlwaysBeADraw(): void
    {
        $this->record(new FightParticipantHadADrawDomainEvent($this->id, $this->initiator->characterId()));
        $this->record(new FightParticipantHadADrawDomainEvent($this->id, $this->opponent->characterId()));
    }

    private function onFightEndsWithAClearWinner(AbstractFightParticipant $winner, AbstractFightParticipant $loser): void
    {
        $this->winnerId = FightWinnerId::fromOther($winner->characterId());

        $this->record(new FightParticipantHadAWinDomainEvent($this->id, $winner->characterId()));
        $this->record(new FightParticipantHadALossDomainEvent($this->id, $loser->characterId(), $this->date));
    }

    /**
     * @param ArrayIterator<int, FightTurn> $turnsIterator
     */
    private function computeTurnsUntilResult(
        AbstractFightParticipant $attacker,
        AbstractFightParticipant $defender,
        Dice $dice,
        UuidGenerator $uuidGenerator,
        FightTurnIndex $turnIndex,
        ArrayIterator $turnsIterator,
    ): void {
        $diceRoll     = $dice->roll($attacker->attack()->value());
        $attackValue  = $attacker->totalAttackValue($diceRoll);
        $damagesDealt = $defender->dealDamages($attackValue);

        $turnId = new FightTurnId($uuidGenerator->uuid4());
        $turnsIterator->append(FightTurn::create($this, $turnId, $attacker, $defender, $turnIndex, $diceRoll, $damagesDealt));

        if ($defender->isStillAlive()) {
            $nextTurn = $turnIndex->nextTurn();
            // On next turn, the defender is now the attacker and vice versa.
            $this->computeTurnsUntilResult($defender, $attacker, $dice, $uuidGenerator, $nextTurn, $turnsIterator);
        } else {
            $this->turns = $turnsIterator->getArrayCopy();
            $this->onFightEndsWithAClearWinner($attacker, $defender);
        }
    }
}
