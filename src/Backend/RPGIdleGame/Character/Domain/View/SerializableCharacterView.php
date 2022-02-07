<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Domain\View;

use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
use Kishlin\Backend\Shared\Domain\View\SerializableView;

final class SerializableCharacterView extends SerializableView
{
    private string $id;
    private string $name;
    private string $owner;
    private int $skillPoints;
    private int $health;
    private int $attack;
    private int $defense;
    private int $magik;
    private int $rank;

    /**
     * @return array{id: string, name: string, owner: string, skill_points: int, health: int, attack: int, defense: int, magik: int, rank: int}
     */
    public function __serialize(): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'owner'        => $this->owner,
            'skill_points' => $this->skillPoints,
            'health'       => $this->health,
            'attack'       => $this->attack,
            'defense'      => $this->defense,
            'magik'        => $this->magik,
            'rank'         => $this->rank,
        ];
    }

    /**
     * @param array{id: string, name: string, owner: string, skill_points: int, health: int, attack: int, defense: int, magik: int, rank: int} $data
     */
    public function __unserialize(array $data): void
    {
        [
            'id'           => $this->id,
            'name'         => $this->name,
            'owner'        => $this->owner,
            'skill_points' => $this->skillPoints,
            'health'       => $this->health,
            'attack'       => $this->attack,
            'defense'      => $this->defense,
            'magik'        => $this->magik,
            'rank'         => $this->rank,
        ] = $data;
    }

    /**
     * @param array{id: string, name: string, owner: string, skill_points: int, health: int, attack: int, defense: int, magik: int, rank: int} $source
     */
    public static function fromSource(array $source): self
    {
        $view = new self();

        $view->__unserialize($source);

        return $view;
    }

    public static function fromEntity(Character $character): self
    {
        $view = new self();

        $view->id          = $character->id()->value();
        $view->name        = $character->name()->value();
        $view->owner       = $character->owner()->value();
        $view->skillPoints = $character->skillPoint()->value();
        $view->health      = $character->health()->value();
        $view->attack      = $character->attack()->value();
        $view->defense     = $character->defense()->value();
        $view->magik       = $character->magik()->value();
        $view->rank        = $character->rank()->value();

        return $view;
    }
}
