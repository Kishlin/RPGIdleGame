<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Security;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Kishlin\Backend\Shared\Domain\Security\ParsingTokenFailedException;
use Kishlin\Backend\Shared\Domain\Security\TokenParser;
use Kishlin\Backend\Shared\Domain\Security\TokenPayload;

final class TokenParserUsingFirebase implements TokenParser
{
    public function __construct(
        private string $secretKey,
        private string $algorithm,
        private bool $expirationClaimIsRequired,
    ) {
    }

    /**
     * @throws ParsingTokenFailedException
     */
    public function payloadFromToken(string $refreshToken): TokenPayload
    {
        try {
            // 'iat' and 'exp' claims are verified by JWT::decode(). We do not have to make the checks ourselves.
            /** @var array{user: string} $token */
            $token = (array) JWT::decode($refreshToken, new Key($this->secretKey, $this->algorithm));
        } catch (\Throwable $e) {
            throw new ParsingTokenFailedException();
        }

        if ($this->expirationIsRequiredButMissing($token) || false === $this->tokenHasAllRequiredKeys($token)) {
            throw new ParsingTokenFailedException();
        }

        return $this->payloadDTOFromToken($token);
    }

    /**
     * @param array<string, mixed> $token
     */
    private function expirationIsRequiredButMissing(array $token): bool
    {
        return $this->expirationClaimIsRequired && false === array_key_exists('exp', $token);
    }

    /**
     * @param array<string, mixed> $token
     */
    private function tokenHasAllRequiredKeys(array $token): bool
    {
        return empty(array_diff(['user'], array_keys($token)));
    }

    /**
     * @param array{user: string} $token
     */
    private function payloadDTOFromToken(array $token): TokenPayload
    {
        return TokenPayload::fromScalars($token['user']);
    }
}
