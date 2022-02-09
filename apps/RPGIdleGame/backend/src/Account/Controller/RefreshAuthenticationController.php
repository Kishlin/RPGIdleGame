<?php

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\Account\Controller;

use Kishlin\Backend\Account\Application\RefreshAuthentication\RefreshAuthenticationCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Infrastructure\Security\Authorization\BearerAuthorization;
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
        $authorization = BearerAuthorization::fromHeader($request->headers->get('Authorization') ?? '');

        $data = $this->commandBus->execute(
            RefreshAuthenticationCommand::fromScalars(
                $authorization->token(),
            )
        );

        return new JsonResponse($data, Response::HTTP_OK, json: true);
    }
}
