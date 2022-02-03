<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Account;

use Kishlin\Backend\Account\Application\Authenticate\AuthenticationGenerator;

final class AuthenticationGeneratorStub implements AuthenticationGenerator
{
    public function generateToken(string $userId): string
    {
        return "token-{$userId}";
    }

    public function generateRefreshToken(string $userId, string $salt): string
    {
        return "token-{$userId}-salt-{$salt}";
    }
}
