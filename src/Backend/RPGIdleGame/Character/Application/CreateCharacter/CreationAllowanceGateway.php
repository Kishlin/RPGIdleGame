<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Application\CreateCharacter;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface CreationAllowanceGateway
{
    public function isAllowedToCreateACharacter(UuidValueObject $characterCountOwner): bool;
}
