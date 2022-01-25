<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\CharacterCount\Domain;

use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountOwner;

interface CharacterCountGateway
{
    public function save(CharacterCount $characterCount): void;

    public function hasReachedLimit(CharacterCountOwner $characterCountOwner, int $countLimit): bool;
}
