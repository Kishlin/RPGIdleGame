<?php

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\Account\Controller;

use Kishlin\Apps\RPGIdleGame\Backend\Security\ResponseWithCookieBuilder;
use Kishlin\Backend\Account\Application\RefreshAuthentication\RefreshAuthenticationCommand;
use Kishlin\Backend\Account\Domain\View\SimpleAuthenticationDTO;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Infrastructure\Security\Authorization\JWTAuthorization;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/refresh-authentication', name: 'account_refresh_authentication', methods: [Request::METHOD_POST])]
final class RefreshAuthenticationController
{
    public function __construct(
        private CommandBus $commandBus,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $authorization = JWTAuthorization::fromCookie($request->cookies->get('refreshToken'));

        /** @var SimpleAuthenticationDTO $authentication */
        $authentication = $this->commandBus->execute(
            RefreshAuthenticationCommand::fromScalars(
                $authorization->token(),
            )
        );

        return ResponseWithCookieBuilder::init(new JsonResponse(status: Response::HTTP_OK))
            ->withToken($authentication)
            ->build()
        ;
    }
}
