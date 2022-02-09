<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Infrastructure;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Kishlin\Backend\Account\Application\RefreshAuthentication\ParsingTheRefreshTokenFailedException;
use Kishlin\Backend\Account\Application\RefreshAuthentication\RefreshTokenParser;
use Kishlin\Backend\Account\Application\RefreshAuthentication\RefreshTokenPayload;

final class RefreshTokenParserUsingFirebase implements RefreshTokenParser
{
    public function __construct(
        private string $secretKey,
        private string $algorithm,
    ) {
    }

    /**
     * @throws ParsingTheRefreshTokenFailedException
     */
    public function payloadFromRefreshToken(string $refreshToken): RefreshTokenPayload
    {
        try {
            // 'iat' and 'exp' claims are verified by JWT::decode(). We do not have to make the checks ourselves.
            /** @var array{userId: string, salt: string} $token */
            $token = (array) JWT::decode($refreshToken, new Key($this->secretKey, $this->algorithm));
        } catch (\Throwable $e) {
            throw new ParsingTheRefreshTokenFailedException();
        }

        if (false === $this->tokenHasAllRequiredKeys($token)) {
            throw new ParsingTheRefreshTokenFailedException();
        }

        return $this->payloadDTOFromToken($token);
    }

    /**
     * @param array<string, mixed> $token
     */
    private function tokenHasAllRequiredKeys(array $token): bool
    {
        return empty(array_diff(['user', 'salt'], array_keys($token)));
    }

    /**
     * @param array{userId: string, salt: string} $token
     */
    private function payloadDTOFromToken(array $token): RefreshTokenPayload
    {
        return RefreshTokenPayload::fromScalars($token['user'], $token['salt']);
    }
}
