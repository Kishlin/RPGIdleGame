<?php

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\RPGIdleGame\Character\Controller;

use Kishlin\Apps\RPGIdleGame\Backend\Security\RequesterIdentifier;
use Kishlin\Backend\RPGIdleGame\Character\Application\DistributeSkillPoints\DistributeSkillPointsCommand;
use Kishlin\Backend\RPGIdleGame\Character\Application\ViewCharacter\ViewCharacterQuery;
use Kishlin\Backend\RPGIdleGame\Character\Application\ViewCharacter\ViewCharacterResponse;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/{characterId}',
    name: 'character_update',
    requirements: ['characterId' => '[\w\-]+'],
    methods: [Request::METHOD_PUT]
)]
final class DistributeSkillPointsController
{
    public function __construct(
        private RequesterIdentifier $requesterIdentifier,
        private CommandBus $commandBus,
        private QueryBus $queryBus,
    ) {
    }

    /**
     * @throws BadRequestException
     */
    public function __invoke(Request $request, string $characterId): Response
    {
        $requesterId = $this->requesterIdentifier->fromRequest($request)->id();
        $requestData = $this->requestData($request, $characterId, $requesterId);

        $command = DistributeSkillPointsCommand::fromRequest($requestData);

        $this->commandBus->execute($command);

        /** @var ViewCharacterResponse $response */
        $response = $this->queryBus->ask(
            ViewCharacterQuery::fromScalars($characterId, $requesterId),
        );

        return new JsonResponse($response->characterView(), status: Response::HTTP_OK, json: true);
    }

    /**
     * @throws BadRequestException
     *
     * @return array{characterId: string, requesterId: string, health: int, attack: int, defense: int, magik: int}
     */
    private function requestData(Request $request, string $characterId, string $requesterId): array
    {
        /** @var array{health: int, attack: int, defense: int, magik: int}|false $requestData */
        $requestData = json_decode($request->getContent(), true);

        if (
            false === is_array($requestData)
            || false === empty(array_diff(['health', 'attack', 'defense', 'magik'], array_keys($requestData)))
        ) {
            throw new BadRequestException();
        }

        $requestData['requesterId'] = $requesterId;
        $requestData['characterId'] = $characterId;

        return $requestData;
    }
}
