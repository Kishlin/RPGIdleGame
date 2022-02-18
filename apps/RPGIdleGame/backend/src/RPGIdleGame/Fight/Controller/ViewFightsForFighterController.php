<?php

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\RPGIdleGame\Fight\Controller;

use Kishlin\Apps\RPGIdleGame\Backend\Security\RequesterIdentifier;
use Kishlin\Backend\RPGIdleGame\Fight\Application\ViewFightsForCharacter\ViewFightsForFighterQuery;
use Kishlin\Backend\RPGIdleGame\Fight\Application\ViewFightsForCharacter\ViewFightsForFighterResponse;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/all/{fighterId}',
    name: 'fight_view_for_fighters',
    requirements: ['fighterId' => '[\w\-]+'],
    methods: [Request::METHOD_GET],
)]
final class ViewFightsForFighterController
{
    public function __construct(
        private RequesterIdentifier $requesterIdentifier,
        private QueryBus $queryBus,
    ) {
    }

    public function __invoke(Request $request, string $fighterId): Response
    {
        $requesterId = $this->requesterIdentifier->fromRequest($request)->id();

        /** @var ViewFightsForFighterResponse $response */
        $response = $this->queryBus->ask(
            ViewFightsForFighterQuery::fromScalars($fighterId, $requesterId),
        );

        return new JsonResponse($response->fights(), status: Response::HTTP_OK, json: true);
    }
}
