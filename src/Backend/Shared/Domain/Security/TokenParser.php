<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\Security;

interface TokenParser
{
    /**
     * @throws ParsingTokenFailedException
     */
    public function payloadFromToken(string $refreshToken): TokenPayload;
}
