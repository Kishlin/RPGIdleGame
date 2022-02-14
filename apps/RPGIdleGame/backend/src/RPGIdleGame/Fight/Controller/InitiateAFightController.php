<?php

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\RPGIdleGame\Fight\Controller;

use Kishlin\Apps\RPGIdleGame\Backend\Security\RequesterIdentifier;
use Kishlin\Backend\RPGIdleGame\Fight\Application\InitiateAFight\InitiateAFightCommand;
use Kishlin\Backend\RPGIdleGame\Fight\Application\ViewFight\ViewFightQuery;
use Kishlin\Backend\RPGIdleGame\Fight\Application\ViewFight\ViewFightResponse;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/initiate/{fighterId}',
    name: 'fight_initiate',
    requirements: ['fighterId' => '[\w\-]+'],
    methods: [Request::METHOD_POST],
)]
final class InitiateAFightController
{
    public function __construct(
        private RequesterIdentifier $requesterIdentifier,
        private CommandBus $commandBus,
        private QueryBus $queryBus,
    ) {
    }

    public function __invoke(Request $request, string $fighterId): Response
    {
        $requesterId = $this->requesterIdentifier->identify($request)->id();

        $fightId = $this->commandBus->execute(
            InitiateAFightCommand::fromScalars($fighterId, $requesterId),
        );

        assert($fightId instanceof FightId);

        /** @var ViewFightResponse $fightViewResponse */
        $fightViewResponse = $this->queryBus->ask(
            ViewFightQuery::fromScalars($fightId->value(), $requesterId),
        );

        return new JsonResponse($fightViewResponse->fightView(), Response::HTTP_CREATED, json: true);
    }
}
