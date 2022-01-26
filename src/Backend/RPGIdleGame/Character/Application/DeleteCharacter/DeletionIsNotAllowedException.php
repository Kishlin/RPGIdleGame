<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Application\DeleteCharacter;

use Kishlin\Backend\Shared\Domain\Exception\DomainException;

final class DeletionIsNotAllowedException extends DomainException
{
}
