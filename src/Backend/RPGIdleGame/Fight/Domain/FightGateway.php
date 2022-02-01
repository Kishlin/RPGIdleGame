<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Domain;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightId;

/**
 * Saves, finds, and delete, Fight entities.
 * To get read-only entities for internal use, use the Read Model gateway.
 * To get entity views, intended to be shown to a client, use the View gateway.
 * Finding entities should only be allowed to any services which intend to update an entity.
 */
interface FightGateway
{
    public function save(Fight $fight): void;

    public function findOneById(FightId $fightId): ?Fight;
}
