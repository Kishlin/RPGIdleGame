<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\Security;

interface RefreshTokenParser
{
    /**
     * @throws ParsingTokenFailedException
     */
    public function payloadFromRefreshToken(string $refreshToken): RefreshTokenPayload;
}
