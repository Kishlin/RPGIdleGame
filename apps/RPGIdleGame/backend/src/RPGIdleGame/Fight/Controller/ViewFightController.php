<?php

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\RPGIdleGame\Fight\Controller;

use Kishlin\Apps\RPGIdleGame\Backend\Security\RequesterIdentifier;
use Kishlin\Backend\RPGIdleGame\Fight\Application\ViewFight\ViewFightQuery;
use Kishlin\Backend\RPGIdleGame\Fight\Application\ViewFight\ViewFightResponse;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/{fightId}',
    name: 'fight_view',
    requirements: ['fightId' => '[\w\-]+'],
    methods: [Request::METHOD_GET],
)]
final class ViewFightController
{
    public function __construct(
        private RequesterIdentifier $requesterIdentifier,
        private QueryBus $queryBus,
    ) {
    }

    public function __invoke(Request $request, string $fightId): Response
    {
        $requesterId = $this->requesterIdentifier->identify($request)->id();

        /** @var ViewFightResponse $response */
        $response = $this->queryBus->ask(
            ViewFightQuery::fromScalars($fightId, $requesterId),
        );

        return new JsonResponse($response->fightView(), status: Response::HTTP_OK, json: true);
    }
}
