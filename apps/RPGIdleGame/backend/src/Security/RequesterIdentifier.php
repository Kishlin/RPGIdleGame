<?php

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\Security;

use Kishlin\Backend\Shared\Domain\Security\TokenParser;
use Kishlin\Backend\Shared\Infrastructure\Security\Authorization\JWTAuthorization;
use Symfony\Component\HttpFoundation\Request;

final class RequesterIdentifier
{
    public function __construct(
        private TokenParser $tokenParser,
    ) {
    }

    public function fromRequest(Request $request): Requester
    {
        $tokenDTO = JWTAuthorization::fromCookie($request->cookies->get('token'));

        return $this->fromToken($tokenDTO->token());
    }

    public function fromToken(string $token): Requester
    {
        $tokenPayload = $this->tokenParser->payloadFromToken($token);

        return Requester::fromScalar($tokenPayload->userId());
    }
}
