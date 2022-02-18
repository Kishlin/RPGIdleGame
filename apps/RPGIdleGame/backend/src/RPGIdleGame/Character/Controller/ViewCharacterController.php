<?php

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\RPGIdleGame\Character\Controller;

use Kishlin\Apps\RPGIdleGame\Backend\Security\RequesterIdentifier;
use Kishlin\Backend\RPGIdleGame\Character\Application\ViewCharacter\ViewCharacterQuery;
use Kishlin\Backend\RPGIdleGame\Character\Application\ViewCharacter\ViewCharacterResponse;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/{characterId}',
    name: 'character_view',
    requirements: ['characterId' => '[\w\-]+'],
    methods: [Request::METHOD_GET]
)]
final class ViewCharacterController
{
    public function __construct(
        private RequesterIdentifier $requesterIdentifier,
        private QueryBus $queryBus,
    ) {
    }

    public function __invoke(Request $request, string $characterId): Response
    {
        $query = ViewCharacterQuery::fromScalars(
            characterId: $characterId,
            requesterId: $this->requesterIdentifier->fromRequest($request)->id(),
        );

        /** @var ViewCharacterResponse $response */
        $response = $this->queryBus->ask($query);

        return new JsonResponse($response->characterView(), status: Response::HTTP_OK, json: true);
    }
}
