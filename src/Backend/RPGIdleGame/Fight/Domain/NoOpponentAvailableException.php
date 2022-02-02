<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Domain;

use Kishlin\Backend\Shared\Domain\Exception\DomainException;

final class NoOpponentAvailableException extends DomainException
{
}
