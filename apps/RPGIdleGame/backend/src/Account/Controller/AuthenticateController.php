<?php

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\Account\Controller;

use Kishlin\Apps\RPGIdleGame\Backend\Security\RequesterIdentifier;
use Kishlin\Apps\RPGIdleGame\Backend\Security\ResponseWithCookieBuilder;
use Kishlin\Backend\Account\Application\Authenticate\AuthenticateCommand;
use Kishlin\Backend\Account\Domain\View\AuthenticationDTO;
use Kishlin\Backend\RPGIdleGame\Character\Application\ViewAllCharacter\ViewAllCharactersQuery;
use Kishlin\Backend\RPGIdleGame\Character\Application\ViewAllCharacter\ViewAllCharactersResponse;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Kishlin\Backend\Shared\Infrastructure\Security\Authorization\BasicAuthorization;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/authenticate', name: 'account_authenticate', methods: [Request::METHOD_POST])]
final class AuthenticateController
{
    public function __construct(
        private RequesterIdentifier $requesterIdentifier,
        private CommandBus $commandBus,
        private QueryBus $queryBus,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $authorization = BasicAuthorization::fromHeader($request->headers->get('Authorization') ?? '');

        /** @var AuthenticationDTO $authentication */
        $authentication = $this->commandBus->execute(
            AuthenticateCommand::fromScalars(
                $authorization->username(),
                $authorization->password(),
            )
        );

        /** @var ViewAllCharactersResponse $charactersResponse */
        $charactersResponse = $this->queryBus->ask(
            ViewAllCharactersQuery::fromScalars(
                $this->requesterIdentifier->fromToken($authentication->token())->id(),
            ),
        );

        return ResponseWithCookieBuilder::init(
            new JsonResponse($charactersResponse->viewsList(), status: Response::HTTP_OK, json: true),
        )
            ->withRefreshToken($authentication)
            ->withToken($authentication)
            ->build()
        ;
    }
}
