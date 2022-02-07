<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Domain\View;

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
    private int $fightsCount;
    private int $winsCount;
    private int $drawsCount;
    private int $lossesCount;

    /**
     * @return array{id: string, name: string, owner: string, skill_points: int, health: int, attack: int, defense: int, magik: int, rank: int, fights_count: int, wins_count: int, draws_count: int, losses_count: int}
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
            'fights_count' => $this->fightsCount,
            'wins_count'   => $this->winsCount,
            'draws_count'  => $this->drawsCount,
            'losses_count' => $this->lossesCount,
        ];
    }

    /**
     * @param array{id: string, name: string, owner: string, skill_points: int, health: int, attack: int, defense: int, magik: int, rank: int, fights_count: int, wins_count: int, draws_count: int, losses_count: int} $data
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
            'fights_count' => $this->fightsCount,
            'wins_count'   => $this->winsCount,
            'draws_count'  => $this->drawsCount,
            'losses_count' => $this->lossesCount,
        ] = $data;
    }

    /**
     * @param array{id: string, name: string, owner: string, skill_points: int, health: int, attack: int, defense: int, magik: int, rank: int, fights_count: int, wins_count: int, draws_count: int, losses_count: int} $source
     */
    public static function fromSource(array $source): self
    {
        $view = new self();

        $view->__unserialize($source);

        return $view;
    }
}
