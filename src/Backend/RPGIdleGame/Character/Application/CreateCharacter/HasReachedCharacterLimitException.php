<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Application\CreateCharacter;

use Kishlin\Backend\Shared\Domain\Exception\DomainException;

final class HasReachedCharacterLimitException extends DomainException
{
}
