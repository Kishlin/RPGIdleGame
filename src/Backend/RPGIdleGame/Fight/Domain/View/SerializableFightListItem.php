<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Domain\View;

use Kishlin\Backend\Shared\Domain\View\SerializableView;

final class SerializableFightListItem extends SerializableView
{
    private string $id;

    private ?string $winnerId;

    private string $initiatorName;

    private int $initiatorRank;

    private string $opponentName;

    private int $opponentRank;

    /**
     * @return array{id: string, winner_id: ?string, initiator_name: string, initiator_rank: int, opponent_name: string, opponent_rank: int}
     */
    public function __serialize(): array
    {
        return [
            'id'             => $this->id,
            'winner_id'      => $this->winnerId,
            'initiator_name' => $this->initiatorName,
            'initiator_rank' => $this->initiatorRank,
            'opponent_name'  => $this->opponentName,
            'opponent_rank'  => $this->opponentRank,
        ];
    }

    /**
     * @param array{id: string, winner_id: ?string, initiator_name: string, initiator_rank: int, opponent_name: string, opponent_rank: int} $data
     */
    public function __unserialize(array $data): void
    {
        [
            'id'             => $this->id,
            'winner_id'      => $this->winnerId,
            'initiator_name' => $this->initiatorName,
            'initiator_rank' => $this->initiatorRank,
            'opponent_name'  => $this->opponentName,
            'opponent_rank'  => $this->opponentRank,
        ] = $data;
    }

    /**
     * @param array{id: string, winner_id: ?string, initiator_name: string, initiator_rank: int, opponent_name: string, opponent_rank: int} $source
     */
    public static function fromSource(array $source): self
    {
        $view = new self();

        $view->__unserialize($source);

        return $view;
    }
}
