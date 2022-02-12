<?php

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\RPGIdleGame\Character\Controller;

use Kishlin\Apps\RPGIdleGame\Backend\Security\RequesterIdentifier;
use Kishlin\Backend\RPGIdleGame\Character\Application\DeleteCharacter\DeleteCharacterCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/{characterId}',
    name: 'character_delete',
    requirements: ['characterId' => '[\w\-]+'],
    methods: [Request::METHOD_DELETE]
)]
final class DeleteCharacterController
{
    public function __construct(
        private RequesterIdentifier $requesterIdentifier,
        private CommandBus $commandBus,
    ) {
    }

    public function __invoke(Request $request, string $characterId): Response
    {
        $requesterId = $this->requesterIdentifier->identify($request)->id();

        $command = DeleteCharacterCommand::fromScalars($characterId, $requesterId);

        $this->commandBus->execute($command);

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
