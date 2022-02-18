<?php

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\RPGIdleGame\Character\Controller;

use Kishlin\Apps\RPGIdleGame\Backend\Security\RequesterIdentifier;
use Kishlin\Backend\RPGIdleGame\Character\Application\CreateCharacter\CreateCharacterCommand;
use Kishlin\Backend\RPGIdleGame\Character\Application\ViewCharacter\ViewCharacterQuery;
use Kishlin\Backend\RPGIdleGame\Character\Application\ViewCharacter\ViewCharacterResponse;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
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
        private QueryBus $queryBus,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $owner         = $this->requesterIdentifier->fromRequest($request)->id();
        $characterId   = $this->uuidGenerator->uuid4();
        $characterName = $this->readCharacterNameFromRequestBody($request);

        $characterId = $this->commandBus->execute(
            CreateCharacterCommand::fromRequest([
                'characterId'   => $characterId,
                'ownerUuid'     => $owner,
                'characterName' => $characterName,
            ]),
        );

        assert($characterId instanceof CharacterId);

        /** @var ViewCharacterResponse $response */
        $response = $this->queryBus->ask(
            ViewCharacterQuery::fromScalars(characterId: $characterId->value(), requesterId: $owner),
        );

        return new JsonResponse($response->characterView(), status: Response::HTTP_CREATED, json: true);
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
