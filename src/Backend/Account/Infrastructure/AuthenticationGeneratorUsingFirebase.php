<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Infrastructure;

use Kishlin\Backend\Account\Application\Authenticate\AuthenticationGenerator;
use Kishlin\Backend\Account\Application\RefreshAuthentication\SimpleAuthenticationGenerator;
use Kishlin\Backend\Shared\Infrastructure\Security\JWTGeneratorUsingFirebase;

final class AuthenticationGeneratorUsingFirebase implements AuthenticationGenerator, SimpleAuthenticationGenerator
{
    public function __construct(
        private JWTGeneratorUsingFirebase $tokenGenerator,
    ) {
    }

    public function generateToken(string $userId): string
    {
        return $this->tokenGenerator->token([
            'user' => $userId,
            'exp'  => strtotime('+10 minute'),
        ]);
    }

    public function generateRefreshToken(string $userId, string $salt): string
    {
        return $this->tokenGenerator->token([
            'user' => $userId,
            'exp'  => strtotime('+1 month'),
            'salt' => $salt,
        ]);
    }
}
