<?php

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\Security;

use Kishlin\Backend\Shared\Domain\Security\TokenParser;
use Kishlin\Backend\Shared\Infrastructure\Security\Authorization\BearerAuthorization;
use Symfony\Component\HttpFoundation\Request;

final class RequesterIdentifier
{
    public function __construct(
        private TokenParser $tokenParser,
    ) {
    }

    public function identify(Request $request): Requester
    {
        $tokenDTO = BearerAuthorization::fromHeader($request->headers->get('Authorization') ?? '');

        $tokenPayload = $this->tokenParser->payloadFromToken($tokenDTO->token());

        return Requester::fromScalar($tokenPayload->userId());
    }
}
