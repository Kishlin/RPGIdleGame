<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Domain\View;

use Kishlin\Backend\Shared\Domain\View\JsonableView;

final class JsonableFightListItem extends JsonableView
{
    private string $id;
    private ?string $winnerId;
    private string $initiatorName;
    private int $initiatorRank;
    private string $opponentName;
    private int $opponentRank;

    public function toArray(): array
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
     * @param array{id: string, winner_id: ?string, initiator_name: string, initiator_rank: int, opponent_name: string, opponent_rank: int} $source
     */
    public static function fromSource(array $source): self
    {
        $view = new self();

        [
            'id'             => $view->id,
            'winner_id'      => $view->winnerId,
            'initiator_name' => $view->initiatorName,
            'initiator_rank' => $view->initiatorRank,
            'opponent_name'  => $view->opponentName,
            'opponent_rank'  => $view->opponentRank,
        ] = $source;

        return $view;
    }
}
