<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Application\Authenticate;

use Kishlin\Backend\Account\Domain\AccountReaderGateway;
use Kishlin\Backend\Account\Domain\View\JsonableAuthentication;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;

final class AuthenticateCommandHandler implements CommandHandler
{
    public function __construct(
        private AuthenticationGenerator $authenticationGenerator,
        private AccountReaderGateway $passwordHashReader,
    ) {
    }

    /**
     * @throws AuthenticationDeniedException
     */
    public function __invoke(AuthenticateCommand $command): JsonableAuthentication
    {
        $accountDetails = $this->passwordHashReader->readModelForAuthentication($command->usernameOrEmail());

        if (
            null === $accountDetails
            || false === password_verify($command->password(), $accountDetails->passwordHash())
        ) {
            throw new AuthenticationDeniedException();
        }

        $token        = $this->authenticationGenerator->generateToken($accountDetails->id());
        $refreshToken = $this->authenticationGenerator->generateRefreshToken(
            $accountDetails->id(),
            $accountDetails->salt()
        );

        return JsonableAuthentication::fromScalars($token, $refreshToken);
    }
}
