<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Domain;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

/**
 * Saves, finds, and delete, FightOpponent entities.
 * To get read-only entities for internal use, use the Read Model gateway.
 * To get entity views, intended to be shown to a client, use the View gateway.
 * Finding entities should only be allowed to any services which intend to update an entity.
 */
interface FightOpponentGateway
{
    /**
     * @throws NoOpponentAvailableException if there is no opponent available
     *
     * @return FightOpponent An opponent for the requesting initiator. The choice of an opponent is primarily based on
     *                       the rank difference between the characters (the least the better), then on the number of
     *                       fights already fought between the characters (hte least the better). If many possible
     *                       opponents match, a random pick is accepted.
     */
    public function createFromExternalDetailsOfAnAvailableOpponent(UuidValueObject $initiatorId): FightOpponent;
}
