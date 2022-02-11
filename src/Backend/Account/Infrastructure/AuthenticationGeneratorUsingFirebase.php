<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Infrastructure;

use InvalidArgumentException;
use Kishlin\Backend\Account\Application\Authenticate\AuthenticationGenerator;
use Kishlin\Backend\Account\Application\RefreshAuthentication\SimpleAuthenticationGenerator;
use Kishlin\Backend\Shared\Infrastructure\Security\JWTGeneratorUsingFirebase;

final class AuthenticationGeneratorUsingFirebase implements AuthenticationGenerator, SimpleAuthenticationGenerator
{
    public function __construct(
        private JWTGeneratorUsingFirebase $tokenGenerator,
        private bool $expirationClaimIsRequired,
        private string $refreshTokenExpiration = '',
        private string $tokenExpiration = '',
    ) {
        if ($expirationClaimIsRequired && (empty($this->refreshTokenExpiration) || empty($this->tokenExpiration))) {
            throw new InvalidArgumentException('You must provide expiration values when the exp claim is required.');
        }
    }

    public function generateToken(string $userId): string
    {
        $payload = [
            'user' => $userId,
        ];

        if ($this->expirationClaimIsRequired) {
            $payload['exp'] = strtotime($this->tokenExpiration);
        }

        return $this->tokenGenerator->token($payload);
    }

    public function generateRefreshToken(string $userId, string $salt): string
    {
        $payload = [
            'user' => $userId,
            'salt' => $salt,
        ];

        if ($this->expirationClaimIsRequired) {
            $payload['exp'] = strtotime($this->refreshTokenExpiration);
        }

        return $this->tokenGenerator->token($payload);
    }
}
