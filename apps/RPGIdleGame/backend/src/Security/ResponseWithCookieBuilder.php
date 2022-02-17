<?php

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\Security;

use Kishlin\Backend\Account\Domain\View\AuthenticationDTO;
use Kishlin\Backend\Account\Domain\View\SimpleAuthenticationDTO;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

final class ResponseWithCookieBuilder
{
    private function __construct(
        private Response $response,
    ) {
    }

    public static function init(Response $response): self
    {
        return new self($response);
    }

    public function withToken(SimpleAuthenticationDTO|AuthenticationDTO $dto): self
    {
        $this->response->headers->setCookie(new Cookie(
            name: 'token',
            value: $dto->token(),
            httpOnly: true,
        ));

        return $this;
    }

    public function withRefreshToken(AuthenticationDTO $dto): self
    {
        $this->response->headers->setCookie(new Cookie(
            name: 'refreshToken',
            value: $dto->refreshToken(),
            httpOnly: true,
        ));

        return $this;
    }

    public function build(): Response
    {
        return $this->response;
    }
}
