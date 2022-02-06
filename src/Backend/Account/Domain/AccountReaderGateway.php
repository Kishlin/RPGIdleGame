<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Domain;

use Kishlin\Backend\Account\Domain\ReadModel\AccountDetailsForAuthentication;

interface AccountReaderGateway
{
    public function readModelForAuthentication(string $usernameOrEmail): ?AccountDetailsForAuthentication;

    public function theUserExistsWithThisSalt(string $userId, string $salt): bool;
}
