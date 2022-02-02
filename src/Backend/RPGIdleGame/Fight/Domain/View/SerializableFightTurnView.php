<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Domain\View;

use Kishlin\Backend\Shared\Domain\View\SerializableView;

final class SerializableFightTurnView extends SerializableView
{
    private string $character_name;
    private int $index;
    private int $attacker_attack;
    private int $attacker_magik;
    private int $attacker_dice_roll;
    private int $defender_defense;
    private int $damage_dealt;
    private int $defender_health;

    /**
     * @return array{character_name: string, index: int, attacker_attack: int, attacker_magik: int, attacker_dice_roll: int, defender_defense: int, damage_dealt: int, defender_health: int}
     */
    public function __serialize(): array
    {
        return [
            'character_name'     => $this->character_name,
            'index'              => $this->index,
            'attacker_attack'    => $this->attacker_attack,
            'attacker_magik'     => $this->attacker_magik,
            'attacker_dice_roll' => $this->attacker_dice_roll,
            'defender_defense'   => $this->defender_defense,
            'damage_dealt'       => $this->damage_dealt,
            'defender_health'    => $this->defender_health,
        ];
    }

    /**
     * @param array{character_name: string, index: int, attacker_attack: int, attacker_magik: int, attacker_dice_roll: int, defender_defense: int, damage_dealt: int, defender_health: int} $data
     */
    public function __unserialize(array $data): void
    {
        [
            'character_name'     => $this->character_name,
            'index'              => $this->index,
            'attacker_attack'    => $this->attacker_attack,
            'attacker_magik'     => $this->attacker_magik,
            'attacker_dice_roll' => $this->attacker_dice_roll,
            'defender_defense'   => $this->defender_defense,
            'damage_dealt'       => $this->damage_dealt,
            'defender_health'    => $this->defender_health,
        ] = $data;
    }

    /**
     * @param array{character_name: string, index: int, attacker_attack: int, attacker_magik: int, attacker_dice_roll: int, defender_defense: int, damage_dealt: int, defender_health: int} $source
     */
    public static function fromSource(array $source): self
    {
        $view = new self();

        $view->__unserialize($source);

        return $view;
    }
}
