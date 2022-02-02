<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Domain\View;

use Kishlin\Backend\Shared\Domain\View\SerializableView;

final class SerializableFightView extends SerializableView
{
    private string $id;

    private SerializableFightParticipantView $initiator;

    private SerializableFightParticipantView $opponent;

    /**
     * @var SerializableFightTurnView[]
     */
    private array $turns;

    private ?string $winnerId;

    /**
     * @return array{id: string, initiator: array{account_username: string, character_name: string, health: int, attack: int, defense: int, magik: int, rank: int}, opponent: array{account_username: string, character_name: string, health: int, attack: int, defense: int, magik: int, rank: int}, turns: array<array{character_name: string, index: int, attacker_attack: int, attacker_magik: int, attacker_dice_roll: int, defender_defense: int, damage_dealt: int, defender_health: int}>, winner_id: ?string}
     */
    public function __serialize(): array
    {
        $serializeTurns = static fn (SerializableFightTurnView $view) => $view->__serialize();

        return [
            'id'        => $this->id,
            'initiator' => $this->initiator->__serialize(),
            'opponent'  => $this->opponent->__serialize(),
            'turns'     => array_map($serializeTurns, $this->turns),
            'winner_id' => $this->winnerId,
        ];
    }

    /**
     * @param array{id: string, initiator: array{account_username: string, character_name: string, health: int, attack: int, defense: int, magik: int, rank: int}, opponent: array{account_username: string, character_name: string, health: int, attack: int, defense: int, magik: int, rank: int}, turns: array<array{character_name: string, index: int, attacker_attack: int, attacker_magik: int, attacker_dice_roll: int, defender_defense: int, damage_dealt: int, defender_health: int}>, winner_id: ?string} $data
     */
    public function __unserialize(array $data): void
    {
        [
            'id'        => $this->id,
            'initiator' => $initiator,
            'opponent'  => $opponent,
            'turns'     => $turns,
            'winner_id' => $this->winnerId,
        ] = $data;

        $this->initiator = SerializableFightParticipantView::fromSource($initiator);
        $this->opponent  = SerializableFightParticipantView::fromSource($opponent);

        $this->turns = array_map([SerializableFightTurnView::class, 'fromSource'], $turns);
    }

    /**
     * @param array{id: string, initiator: array{account_username: string, character_name: string, health: int, attack: int, defense: int, magik: int, rank: int}, opponent: array{account_username: string, character_name: string, health: int, attack: int, defense: int, magik: int, rank: int}, turns: array<array{character_name: string, index: int, attacker_attack: int, attacker_magik: int, attacker_dice_roll: int, defender_defense: int, damage_dealt: int, defender_health: int}>, winner_id: ?string} $source
     */
    public static function fromSource(array $source): self
    {
        $view = new self();

        $view->__unserialize($source);

        return $view;
    }
}
