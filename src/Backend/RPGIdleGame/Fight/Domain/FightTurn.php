<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Domain;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightTurnAttackerAttack;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightTurnAttackerDiceRoll;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightTurnAttackerId;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightTurnAttackerMagik;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightTurnDamageDealt;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightTurnDefenderDefense;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightTurnDefenderHealth;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightTurnId;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightTurnIndex;

final class FightTurn
{
    /** @noinspection PhpPropertyOnlyWrittenInspection */
    private function __construct(
        private Fight $fight,
        private FightTurnId $id,
        private FightTurnIndex $index,
        private FightTurnAttackerId $attackerId,
        private FightTurnAttackerAttack $attackerAttack,
        private FightTurnAttackerMagik $attackerMagik,
        private FightTurnAttackerDiceRoll $attackerDiceRoll,
        private FightTurnDefenderDefense $defenderDefense,
        private FightTurnDamageDealt $damageDealt,
        private FightTurnDefenderHealth $defenderHealth,
    ) {
    }

    public static function create(
        Fight $fight,
        FightTurnId $id,
        AbstractFightParticipant $attacker,
        AbstractFightParticipant $defender,
        FightTurnIndex $index,
        int $diceRoll,
        int $damagesDealt,
    ): self {
        return new self(
            fight: $fight,
            id: $id,
            index: $index,
            attackerId: FightTurnAttackerId::fromOther($attacker->id()),
            attackerAttack: FightTurnAttackerAttack::fromOther($attacker->attack()),
            attackerMagik: FightTurnAttackerMagik::fromOther($attacker->magik()),
            attackerDiceRoll: new FightTurnAttackerDiceRoll($diceRoll),
            defenderDefense: FightTurnDefenderDefense::fromOther($defender->defense()),
            damageDealt: new FightTurnDamageDealt($damagesDealt),
            defenderHealth: FightTurnDefenderHealth::fromOther($defender->health()),
        );
    }
}
