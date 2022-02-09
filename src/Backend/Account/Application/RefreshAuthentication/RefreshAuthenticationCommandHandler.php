<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Application\RefreshAuthentication;

use Kishlin\Backend\Account\Domain\AccountReaderGateway;
use Kishlin\Backend\Account\Domain\View\JsonableSimpleAuthentication;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Security\ParsingTokenFailedException;
use Kishlin\Backend\Shared\Domain\Security\RefreshTokenParser;
use Kishlin\Backend\Shared\Domain\Security\RefreshTokenPayload;

final class RefreshAuthenticationCommandHandler implements CommandHandler
{
    public function __construct(
        private RefreshTokenParser $refreshTokenParser,
        private AccountReaderGateway $accountReaderGateway,
        private SimpleAuthenticationGenerator $authenticationGenerator,
    ) {
    }

    /**
     * @throws CannotRefreshAuthenticationException
     */
    public function __invoke(RefreshAuthenticationCommand $command): JsonableSimpleAuthentication
    {
        $payload = $this->extractPayloadFromToken($command);

        $this->refuseAuthenticationIfThePayloadIsInvalid($payload);

        $newToken = $this->authenticationGenerator->generateToken($payload->userId());

        return JsonableSimpleAuthentication::fromScalars(token: $newToken);
    }

    /**
     * @throws CannotRefreshAuthenticationException
     */
    private function extractPayloadFromToken(RefreshAuthenticationCommand $command): RefreshTokenPayload
    {
        try {
            return $this->refreshTokenParser->payloadFromRefreshToken($command->refreshToken());
        } catch (ParsingTokenFailedException $e) {
            throw new CannotRefreshAuthenticationException();
        }
    }

    /**
     * @throws CannotRefreshAuthenticationException
     */
    private function refuseAuthenticationIfThePayloadIsInvalid(RefreshTokenPayload $payload): void
    {
        if (false === $this->accountReaderGateway->theUserExistsWithThisSalt(
            userId: $payload->userId(),
            salt: $payload->salt()
        )
        ) {
            throw new CannotRefreshAuthenticationException();
        }
    }
}
