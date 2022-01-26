<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\Exception;

use RuntimeException;

abstract class DomainException extends RuntimeException implements DomainThrowable
{
}
