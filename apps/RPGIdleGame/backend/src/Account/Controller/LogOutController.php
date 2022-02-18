<?php

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\Account\Controller;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/logout', name: 'account_logout', methods: [Request::METHOD_POST])]
final class LogOutController
{
    public function __invoke(): Response
    {
        $response = new JsonResponse(status: Response::HTTP_NO_CONTENT);

        $this->flagCookiesAsExpired($response);

        return $response;
    }

    private function flagCookiesAsExpired(JsonResponse $response): void
    {
        $response->headers->setCookie(new Cookie('token', expire: '-1 minute', httpOnly: true));
        $response->headers->setCookie(new Cookie('refreshToken', expire: '-1 minute', httpOnly: true));
    }
}
