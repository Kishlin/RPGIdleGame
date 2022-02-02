<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Application\ViewFight;

use Kishlin\Backend\Shared\Domain\Bus\Query\Query;

final class ViewFightQuery implements Query
{
    private function __construct(
        private string $fightId,
        private string $requesterId,
    ) {
    }

    public function fightId(): string
    {
        return $this->fightId;
    }

    public function requesterId(): string
    {
        return $this->requesterId;
    }

    public static function fromScalars(string $fightId, string $requesterId): self
    {
        return new self($fightId, $requesterId);
    }
}
