<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Application\Authenticate;

use Kishlin\Backend\Shared\Domain\Exception\DomainException;

final class AuthenticationDeniedException extends DomainException
{
}
