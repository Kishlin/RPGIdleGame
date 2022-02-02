<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Domain\View;

use Kishlin\Backend\Shared\Domain\View\SerializableView;

final class SerializableFightParticipantView extends SerializableView
{
    private string $accountUsername;
    private string $characterName;
    private int $health;
    private int $attack;
    private int $defense;
    private int $magik;
    private int $rank;

    /**
     * @return array{account_username: string, character_name: string, health: int, attack: int, defense: int, magik: int, rank: int}
     */
    public function __serialize(): array
    {
        return [
            'account_username' => $this->accountUsername,
            'character_name'   => $this->characterName,
            'health'           => $this->health,
            'attack'           => $this->attack,
            'defense'          => $this->defense,
            'magik'            => $this->magik,
            'rank'             => $this->rank,
        ];
    }

    /**
     * @param array{account_username: string, character_name: string, health: int, attack: int, defense: int, magik: int, rank: int} $data
     */
    public function __unserialize(array $data): void
    {
        [
            'account_username' => $this->accountUsername,
            'character_name'   => $this->characterName,
            'health'           => $this->health,
            'attack'           => $this->attack,
            'defense'          => $this->defense,
            'magik'            => $this->magik,
            'rank'             => $this->rank,
        ] = $data;
    }

    /**
     * @param array{account_username: string, character_name: string, health: int, attack: int, defense: int, magik: int, rank: int} $source
     */
    public static function fromSource(array $source): self
    {
        $view = new self();

        $view->__unserialize($source);

        return $view;
    }
}
