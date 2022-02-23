<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Application\Signup;

use Kishlin\Backend\Shared\Domain\Exception\DomainException;

final class AnAccountAlreadyExistsException extends DomainException
{
    public function __construct(
        private string $conflictField,
    ) {
        parent::__construct();
    }

    public function conflictField(): string
    {
        return $this->conflictField;
    }
}
