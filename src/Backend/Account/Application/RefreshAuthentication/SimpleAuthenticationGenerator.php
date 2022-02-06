<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Application\RefreshAuthentication;

interface SimpleAuthenticationGenerator
{
    public function generateToken(string $userId): string;
}
