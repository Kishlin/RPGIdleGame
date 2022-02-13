<?php

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\Account\Controller;

use Kishlin\Backend\Account\Application\Authenticate\AuthenticateCommand;
use Kishlin\Backend\Account\Application\Signup\SignupCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Infrastructure\Security\Authorization\BasicAuthorization;
use Kishlin\Backend\Shared\Infrastructure\Security\Authorization\FailedToDecodeHeaderException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/signup', name: 'account_signup', methods: [Request::METHOD_POST])]
final class SignupController
{
    public function __construct(
        private UuidGenerator $uuidGenerator,
        private CommandBus $commandBus,
    ) {
    }

    /**
     * @throws BadRequestException|FailedToDecodeHeaderException
     */
    public function __invoke(Request $request): Response
    {
        $authorization = BasicAuthorization::fromHeader($request->headers->get('Authorization') ?? '');

        $command = SignupCommand::fromScalars(
            id: $this->uuidGenerator->uuid4(),
            username: $authorization->username(),
            password: $authorization->password(),
            email: $this->readEmailFromRequestBody($request),
        );

        $this->commandBus->execute($command);

        $data = $this->commandBus->execute(
            AuthenticateCommand::fromScalars(
                $authorization->username(),
                $authorization->password(),
            )
        );

        return new JsonResponse($data, Response::HTTP_CREATED, json: true);
    }

    /**
     * @throws BadRequestException
     */
    private function readEmailFromRequestBody(Request $request): string
    {
        $content = json_decode($request->getContent(), true);

        if (false === is_array($content) || false === array_key_exists('email', $content)) {
            throw new BadRequestException();
        }

        return $content['email'];
    }
}
