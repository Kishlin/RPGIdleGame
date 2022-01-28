<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Domain;

use Kishlin\Backend\RPGIdleGame\Character\Application\DistributeSkillPoints\CharacterNotFoundException;
use Kishlin\Backend\RPGIdleGame\Character\Domain\View\CompleteCharacterView;

/**
 * Generates View models of Character entities, to be displayed to clients external to the application.
 * To load entities in a service which wants to update and save, use the entity gateway.
 * To get read-only entities for an internal use, use the Read Model gateway.
 */
interface CharacterViewGateway
{
    /**
     * @throws CharacterNotFoundException
     */
    public function viewOneById(string $characterId, string $requesterId): CompleteCharacterView;

    /**
     * @return CompleteCharacterView[]
     */
    public function viewAllForOwner(string $ownerUuid): array;
}
