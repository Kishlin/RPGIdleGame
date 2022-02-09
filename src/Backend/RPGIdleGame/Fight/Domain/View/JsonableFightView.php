<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Domain\View;

use Kishlin\Backend\Shared\Domain\View\JsonableView;

final class JsonableFightView extends JsonableView
{
    private string $id;

    private JsonableFightParticipantView $initiator;

    private JsonableFightParticipantView $opponent;

    /**
     * @var JsonableFightTurnView[]
     */
    private array $turns;

    private ?string $winnerId;

    public function toArray(): array
    {
        return [
            'id'        => $this->id,
            'initiator' => $this->initiator->toArray(),
            'opponent'  => $this->opponent->toArray(),
            'turns'     => array_map(static fn (JsonableFightTurnView $view)     => $view->toArray(), $this->turns),
            'winner_id' => $this->winnerId,
        ];
    }

    /**
     * @param array{id: string, initiator: array{account_username: string, character_name: string, health: int, attack: int, defense: int, magik: int, rank: int}, opponent: array{account_username: string, character_name: string, health: int, attack: int, defense: int, magik: int, rank: int}, turns: array<array{character_name: string, index: int, attacker_attack: int, attacker_magik: int, attacker_dice_roll: int, defender_defense: int, damage_dealt: int, defender_health: int}>, winner_id: ?string} $source
     */
    public static function fromSource(array $source): self
    {
        $view = new self();

        [
            'id'        => $view->id,
            'initiator' => $initiator,
            'opponent'  => $opponent,
            'turns'     => $turns,
            'winner_id' => $view->winnerId,
        ] = $source;

        $view->initiator = JsonableFightParticipantView::fromSource($initiator);
        $view->opponent  = JsonableFightParticipantView::fromSource($opponent);

        $view->turns = array_map([JsonableFightTurnView::class, 'fromSource'], $turns);

        return $view;
    }
}
