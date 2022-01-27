<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Application\Signup;

use Kishlin\Backend\Account\Domain\ValueObject\AccountEmail;

interface AccountWithEmailGateway
{
    public function thereAlreadyIsAnAccountWithEmail(AccountEmail $accountEmail): bool;
}
