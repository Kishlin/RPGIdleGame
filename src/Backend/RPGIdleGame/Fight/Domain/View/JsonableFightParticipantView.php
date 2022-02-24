<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Domain\View;

use Kishlin\Backend\Shared\Domain\View\JsonableView;

final class JsonableFightParticipantView extends JsonableView
{
    private string $accountUsername;
    private string $characterName;
    private string $characterId;
    private int $health;
    private int $attack;
    private int $defense;
    private int $magik;
    private int $rank;

    public function toArray(): array
    {
        return [
            'account_username' => $this->accountUsername,
            'character_name'   => $this->characterName,
            'character_id'     => $this->characterId,
            'health'           => $this->health,
            'attack'           => $this->attack,
            'defense'          => $this->defense,
            'magik'            => $this->magik,
            'rank'             => $this->rank,
        ];
    }

    /**
     * @param array{account_username: string, character_name: string, character_id: string, health: int, attack: int, defense: int, magik: int, rank: int} $source
     */
    public static function fromSource(array $source): self
    {
        $view = new self();

        [
            'account_username' => $view->accountUsername,
            'character_name'   => $view->characterName,
            'character_id'     => $view->characterId,
            'health'           => $view->health,
            'attack'           => $view->attack,
            'defense'          => $view->defense,
            'magik'            => $view->magik,
            'rank'             => $view->rank,
        ] = $source;

        return $view;
    }
}
