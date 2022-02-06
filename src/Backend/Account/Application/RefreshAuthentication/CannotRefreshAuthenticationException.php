<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Application\RefreshAuthentication;

use Kishlin\Backend\Shared\Domain\Exception\DomainException;

final class CannotRefreshAuthenticationException extends DomainException
{
}
