<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Security;

use Firebase\JWT\JWT;

final class JWTGeneratorUsingFirebase
{
    public function __construct(
        private string $secretKey,
        private string $hostname,
        private string $algorithm,
    ) {
    }

    /**
     * @param array<string, mixed> $additionalPayload
     */
    public function token(array $additionalPayload): string
    {
        $basePayload = [
            'iss' => $this->hostname,
            'aud' => $this->hostname,
            'iat' => strtotime('now'),
        ];

        return JWT::encode(array_merge($basePayload, $additionalPayload), $this->secretKey, $this->algorithm);
    }
}
