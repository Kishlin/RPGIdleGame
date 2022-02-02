<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Application\InitiateAFight;

use Kishlin\Backend\Shared\Domain\Exception\DomainException;

final class RequesterIsNotAllowedToInitiateFight extends DomainException
{
}
