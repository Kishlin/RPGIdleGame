<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\Exception;

use InvalidArgumentException;

final class InvalidValueException extends InvalidArgumentException implements DomainThrowable
{
    public function __construct(string $message = '')
    {
        parent::__construct($message);
    }
}
