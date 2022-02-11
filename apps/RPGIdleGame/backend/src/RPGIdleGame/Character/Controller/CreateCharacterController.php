<?php

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\RPGIdleGame\Character\Controller;

use Kishlin\Apps\RPGIdleGame\Backend\Security\RequesterIdentifier;
use Kishlin\Backend\RPGIdleGame\Character\Application\CreateCharacter\CreateCharacterCommand;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/create', name: 'character_create', methods: [Request::METHOD_POST])]
final class CreateCharacterController
{
    public function __construct(
        private RequesterIdentifier $requesterIdentifier,
        private UuidGenerator $uuidGenerator,
        private CommandBus $commandBus,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $command = CreateCharacterCommand::fromRequest([
            'characterId'   => $this->uuidGenerator->uuid4(),
            'ownerUuid'     => $this->requesterIdentifier->identify($request),
            'characterName' => $this->readCharacterNameFromRequestBody($request),
        ]);

        $characterId = $this->commandBus->execute($command);

        assert($characterId instanceof CharacterId);

        return new JsonResponse(['characterId' => $characterId->value()], Response::HTTP_CREATED);
    }

    /**
     * @throws BadRequestException
     */
    private function readCharacterNameFromRequestBody(Request $request): string
    {
        $content = json_decode($request->getContent(), true);

        if (false === is_array($content) || false === array_key_exists('characterName', $content)) {
            throw new BadRequestException();
        }

        return $content['characterName'];
    }
}
