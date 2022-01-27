<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Domain;

use Kishlin\Backend\RPGIdleGame\Character\Application\DistributeSkillPoints\CharacterNotFoundException;
use Kishlin\Backend\RPGIdleGame\Character\Domain\View\CompleteCharacterView;

interface CharacterViewer
{
    /**
     * @throws CharacterNotFoundException
     */
    public function viewOneById(string $characterId): CompleteCharacterView;
}
