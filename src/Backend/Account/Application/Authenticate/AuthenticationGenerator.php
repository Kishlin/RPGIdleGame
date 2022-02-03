<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Application\Authenticate;

interface AuthenticationGenerator
{
    public function generateToken(string $userId): string;

    public function generateRefreshToken(string $userId, string $salt): string;
}
