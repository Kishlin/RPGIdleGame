<?php

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\Account\Controller;

use Kishlin\Backend\Account\Application\Authenticate\AuthenticateCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Infrastructure\Security\Authorization\BasicAuthorization;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/authenticate', name: 'account_authenticate', methods: [Request::METHOD_POST])]
final class AuthenticateController
{
    public function __construct(
        private CommandBus $commandBus,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $authorization = BasicAuthorization::fromHeader($request->headers->get('Authorization') ?? '');

        $data = $this->commandBus->execute(
            AuthenticateCommand::fromScalars(
                $authorization->username(),
                $authorization->password(),
            )
        );

        return new JsonResponse($data, Response::HTTP_OK, json: true);
    }
}
