<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Domain\View;

use Kishlin\Backend\Shared\Domain\View\JsonableView;

final class JsonableCharacterView extends JsonableView
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

    public function toArray(): array
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
     * @param array{id: string, name: string, owner: string, skill_points: int, health: int, attack: int, defense: int, magik: int, rank: int, fights_count: int, wins_count: int, draws_count: int, losses_count: int} $source
     */
    public static function fromSource(array $source): self
    {
        $view = new self();

        [
            'id'           => $view->id,
            'name'         => $view->name,
            'owner'        => $view->owner,
            'skill_points' => $view->skillPoints,
            'health'       => $view->health,
            'attack'       => $view->attack,
            'defense'      => $view->defense,
            'magik'        => $view->magik,
            'rank'         => $view->rank,
            'fights_count' => $view->fightsCount,
            'wins_count'   => $view->winsCount,
            'draws_count'  => $view->drawsCount,
            'losses_count' => $view->lossesCount,
        ] = $source;

        return $view;
    }
}
