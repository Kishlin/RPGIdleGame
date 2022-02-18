<?php

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\RPGIdleGame\Character\Controller;

use Kishlin\Apps\RPGIdleGame\Backend\Security\RequesterIdentifier;
use Kishlin\Backend\RPGIdleGame\Character\Application\ViewAllCharacter\ViewAllCharactersQuery;
use Kishlin\Backend\RPGIdleGame\Character\Application\ViewAllCharacter\ViewAllCharactersResponse;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/all', name: 'character_all', methods: [Request::METHOD_GET])]
final class ViewAllCharactersController
{
    public function __construct(
        private RequesterIdentifier $requesterIdentifier,
        private QueryBus $queryBus,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $query = ViewAllCharactersQuery::fromScalars(
            requesterId: $this->requesterIdentifier->fromRequest($request)->id(),
        );

        /** @var ViewAllCharactersResponse $response */
        $response = $this->queryBus->ask($query);

        return new JsonResponse($response->viewsList(), status: Response::HTTP_OK, json: true);
    }
}
