<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\RPGIdleGame\Fight;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\Fight;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightGateway;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightId;

final class FightGatewaySpy implements FightGateway
{
    /** @var array<string, Fight> */
    private array $fights = [];

    public function save(Fight $fight): void
    {
        $this->fights[$fight->id()->value()] = $fight;
    }

    public function findOneById(FightId $fightId): ?Fight
    {
        return $this->fights[$fightId->value()] ?? null;
    }

    public function has(string $fightId): bool
    {
        return array_key_exists($fightId, $this->fights);
    }
}
