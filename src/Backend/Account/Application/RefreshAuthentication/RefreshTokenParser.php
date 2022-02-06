<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Application\RefreshAuthentication;

interface RefreshTokenParser
{
    /**
     * @throws ParsingTheRefreshTokenFailedException
     */
    public function payloadFromRefreshToken(string $refreshToken): RefreshTokenPayload;
}
