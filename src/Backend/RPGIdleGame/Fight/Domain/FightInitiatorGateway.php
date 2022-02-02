<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Domain;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

/**
 * Saves, finds, and delete, FightInitiator entities.
 * To get read-only entities for internal use, use the Read Model gateway.
 * To get entity views, intended to be shown to a client, use the View gateway.
 * Finding entities should only be allowed to any services which intend to update an entity.
 */
interface FightInitiatorGateway
{
    public function createFromExternalDetailsOfInitiator(UuidValueObject $initiatorId): FightInitiator;
}
