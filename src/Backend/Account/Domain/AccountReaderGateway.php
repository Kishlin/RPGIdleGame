<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Domain;

use Kishlin\Backend\Account\Domain\ReadModel\AccountDetailsForAuthentication;

interface AccountReaderGateway
{
    public function readModelForAuthentication(string $usernameOrEmail): ?AccountDetailsForAuthentication;
}
