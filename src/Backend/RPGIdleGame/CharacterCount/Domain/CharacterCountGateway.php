<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\CharacterCount\Domain;

use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountOwner;

/**
 * Saves, and finds, CharacterCount entities.
 * To get read-only entities for internal use, use the Read Model gateway.
 * To get entity views, intended to be shown to a client, use the View gateway.
 * Finding entities should only be allowed to any services which intend to update an entity.
 */
interface CharacterCountGateway
{
    public function save(CharacterCount $characterCount): void;

    public function findForOwner(CharacterCountOwner $characterCountOwner): ?CharacterCount;
}
