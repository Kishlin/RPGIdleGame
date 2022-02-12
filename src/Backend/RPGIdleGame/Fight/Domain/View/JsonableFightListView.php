<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Domain\View;

use Kishlin\Backend\Shared\Domain\View\JsonableView;

final class JsonableFightListView extends JsonableView
{
    /**
     * @param array<array{id: string, winner_id: ?string, initiator_name: string, initiator_rank: int, opponent_name: string, opponent_rank: int}> $fights
     */
    private function __construct(
        private array $fights,
    ) {
    }

    /**
     * @return array<array{id: string, winner_id: ?string, initiator_name: string, initiator_rank: int, opponent_name: string, opponent_rank: int}>
     */
    public function toArray(): array
    {
        return $this->fights;
    }

    /**
     * @param array<array{id: string, winner_id: ?string, initiator_name: string, initiator_rank: int, opponent_name: string, opponent_rank: int}> $source
     */
    public static function fromSource(array $source): self
    {
        return new self($source);
    }
}
