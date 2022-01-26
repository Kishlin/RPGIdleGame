<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Application\Signup;

use Kishlin\Backend\Account\Domain\ValueObject\AccountEmail;
use Kishlin\Backend\Shared\Domain\Exception\DomainException;

final class AnAccountAlreadyUsesTheEmailException extends DomainException
{
    public function __construct(
        private AccountEmail $accountEmail
    ) {
        parent::__construct();
    }

    public function takenEmail(): AccountEmail
    {
        return $this->accountEmail;
    }
}
