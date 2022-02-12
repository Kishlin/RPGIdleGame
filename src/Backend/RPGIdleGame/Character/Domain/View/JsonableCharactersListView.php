<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Domain\View;

use Kishlin\Backend\Shared\Domain\View\JsonableView;

final class JsonableCharactersListView extends JsonableView
{
    /**
     * @param array<array{id: string, name: string, owner: string, skill_points: int, health: int, attack: int, defense: int, magik: int, rank: int, fights_count: int, wins_count: int, draws_count: int, losses_count: int}> $characters
     */
    private function __construct(
        private array $characters
    ) {
    }

    /**
     * @return array<array{id: string, name: string, owner: string, skill_points: int, health: int, attack: int, defense: int, magik: int, rank: int, fights_count: int, wins_count: int, draws_count: int, losses_count: int}>
     */
    public function toArray(): array
    {
        return $this->characters;
    }

    /**
     * @param array<array{id: string, name: string, owner: string, skill_points: int, health: int, attack: int, defense: int, magik: int, rank: int, fights_count: int, wins_count: int, draws_count: int, losses_count: int}> $source
     */
    public static function fromSource(array $source): self
    {
        return new self($source);
    }
}
