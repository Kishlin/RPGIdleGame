<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\CharacterCount\Application\OnCharacterCreated;

use Kishlin\Backend\Shared\Domain\Exception\DomainException;

final class CharacterCountNotFoundException extends DomainException
{
}
