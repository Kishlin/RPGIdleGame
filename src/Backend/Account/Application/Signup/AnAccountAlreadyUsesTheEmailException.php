<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Application\Signup;

use Kishlin\Backend\Account\Domain\ValueObject\AccountEmail;
use RuntimeException;

final class AnAccountAlreadyUsesTheEmailException extends RuntimeException
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
