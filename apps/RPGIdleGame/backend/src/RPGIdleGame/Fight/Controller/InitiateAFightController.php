<?php

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\RPGIdleGame\Fight\Controller;

use Kishlin\Apps\RPGIdleGame\Backend\Security\RequesterIdentifier;
use Kishlin\Backend\RPGIdleGame\Fight\Application\InitiateAFight\InitiateAFightCommand;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
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
    ) {
    }

    public function __invoke(Request $request, string $fighterId): Response
    {
        $requesterId = $this->requesterIdentifier->identify($request)->id();

        $fightId = $this->commandBus->execute(
            InitiateAFightCommand::fromScalars($fighterId, $requesterId),
        );

        assert($fightId instanceof FightId);

        return new JsonResponse(['fightId' => $fightId->value()], Response::HTTP_CREATED);
    }
}
