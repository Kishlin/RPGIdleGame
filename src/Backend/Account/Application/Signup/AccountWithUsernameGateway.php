<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Application\Signup;

use Kishlin\Backend\Account\Domain\ValueObject\AccountUsername;

interface AccountWithUsernameGateway
{
    public function thereAlreadyIsAnAccountWithUsername(AccountUsername $accountUsername): bool;
}
