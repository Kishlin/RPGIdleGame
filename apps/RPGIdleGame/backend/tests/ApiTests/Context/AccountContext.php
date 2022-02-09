<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\Context;

use Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\HTTPClient\Request;
use PHPUnit\Framework\Assert;

final class AccountContext extends RPGIdleGameAPIContext
{
    protected ?string $accountId = null;

    private const REFRESH_TOKEN_WITH_NO_EXPIRATION = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJjNmRjM2ZkOTNmNTYiLCJhdWQiOiJjNmRjM2ZkOTNmNTYiLCJpYXQiOjE2NDQ0MTczNDYsInVzZXIiOiI3ZDM4Nzc0MC01YzE1LTQ3MTItYmRjZi01MTI2YzI4ZmMxMGEiLCJzYWx0IjoiMWM5Y2YzZDM1YzZhMmFhYTY4NWEzOWRmY2JmMzRlNGRjMzgxMzJiNCJ9.4lfYorjgyPRFtvCe7OvSffgycSm_vPO0swxR5wmonh4';
    private const REFRESH_TOKEN_EXPIRED            = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJjNmRjM2ZkOTNmNTYiLCJhdWQiOiJjNmRjM2ZkOTNmNTYiLCJpYXQiOjE2NDQ0MTczNDYsInVzZXIiOiI3ZDM4Nzc0MC01YzE1LTQ3MTItYmRjZi01MTI2YzI4ZmMxMGEiLCJleHAiOjE2NDQ0MTczNDYsInNhbHQiOiIxYzljZjNkMzVjNmEyYWFhNjg1YTM5ZGZjYmYzNGU0ZGMzODEzMmI0In0.B4Oayevt44xPOJQJN30kmFnVmd630P1dGTz6Cwfzwas';

    /**
     * @Given /^a client has an account$/
     * @Given /^an account already exists with the email$/
     *
     * @noinspection SpellCheckingInspection
     */
    public function aClientHasAnAccount(): void
    {
        self::database()->exec(
            <<<'SQL'
INSERT INTO accounts (id, username, email, password, salt, is_active)
VALUES ('7d387740-5c15-4712-bdcf-5126c28fc10a', 'User', 'user@example.com', '$argon2i$v=19$m=65536,t=4,p=1$LkdneGtiWW1ZaXJIM3QxNg$u4J11lsQJLZDbrj0B5qpeMSzm7ST/ES7Pk+NJ3M2LMU', '1c9cf3d35c6a2aaa685a39dfcbf34e4dc38132b4', true);

INSERT INTO character_counts (owner_id, character_count, reached_limit)
VALUES ('7d387740-5c15-4712-bdcf-5126c28fc10a', 0, false);
SQL
        );
    }

    /**
     * @When /^a client creates an account$/
     * @When /^a client creates an account with the same email$/
     */
    public function aClientCreatesAnAccount(): void
    {
        $this->response = self::client()->post(new Request(
            uri: '/account/signup',
            headers: [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode('User:password'),
            ],
            params: ['email' => 'user@example.com']
        ));

        if (201 === $this->response->httpCode()) {
            /** @var array{accountId: string} $decodedBody */
            $decodedBody     = $this->response->decodedBody();
            $this->accountId = $decodedBody['accountId'];
        } else {
            $this->accountId = null;
        }
    }

    /**
     * @When /^a client authenticates with the correct credentials$/
     */
    public function aClientAuthenticatesWithTheCorrectCredentials(): void
    {
        $this->response = self::client()->post(new Request(
            uri: '/account/authenticate',
            headers: [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode('User:password'),
            ],
        ));;
    }

    /**
     * @When /^a client refreshes its authentication with a valid refresh token$/
     */
    public function aClientRefreshesItsAuthenticationWithAValidRefreshToken(): void
    {
        $this->response = self::client()->post(new Request(
            uri: '/account/refresh-authentication',
            headers: [
                'Content-Type: application/json',
                'Authorization: Bearer ' . self::REFRESH_TOKEN_WITH_NO_EXPIRATION,
            ],
        ));;
    }

    /**
     * @When /^a client tries to refresh with an expired refresh token$/
     */
    public function aClientTriesToRefreshWithAnExpiredRefreshToken(): void
    {
        $this->response = self::client()->post(new Request(
            uri: '/account/refresh-authentication',
            headers: [
                'Content-Type: application/json',
                'Authorization: Bearer ' . self::REFRESH_TOKEN_EXPIRED,
            ],
        ));;
    }

    /**
     * @Then /^its credentials are registered$/
     */
    public function itsCredentialsAreRegistered(): void
    {
        Assert::assertNotNull($this->accountId);

        $count = self::database()->fetchOne('SELECT count(1) FROM accounts WHERE id = :id', ['id' => $this->accountId]);

        Assert::assertSame(1, $count);
    }

    /**
     * @Then /^a fresh character count is registered$/
     */
    public function aFreshCharacterCountIsRegistered(): void
    {
        Assert::assertNotNull($this->accountId);

        $count = self::database()->fetchOne(
            'SELECT count(1) FROM character_counts WHERE owner_id = :id',
            ['id' => $this->accountId],
        );

        Assert::assertSame(1, $count);
    }

    /**
     * @Then /^the authentication was authorized$/
     */
    public function theAuthenticationWasAuthorized(): void
    {
        Assert::assertSame(200, $this->response->httpCode());

        /** @var array<string, mixed> $decodedBody */
        $decodedBody = $this->response->decodedBody();

        Assert::assertIsArray($decodedBody);
        Assert::assertArrayHasKey('token', $decodedBody);
        Assert::assertArrayHasKey('refreshToken', $decodedBody);
    }

    /**
     * @Then /^the renewed authentication was returned$/
     */
    public function theRenewedAuthenticationWasReturned(): void
    {
        Assert::assertSame(200, $this->response->httpCode());

        /** @var array<string, mixed> $decodedBody */
        $decodedBody = $this->response->decodedBody();

        Assert::assertIsArray($decodedBody);
        Assert::assertArrayHasKey('token', $decodedBody);
    }

    /**
     * @Then /^renewing the authentication was refused$/
     */
    public function renewingTheAuthenticationWasRefused(): void
    {
        Assert::assertSame(401, $this->response->httpCode());
    }

    /**
     * @Then /^it did not register the new account$/
     */
    public function itDidNotRegisterTheNewAccount(): void
    {
        Assert::assertNull($this->accountId);
        Assert::assertSame(409, $this->response->httpCode());
        Assert::assertSame(1, self::database()->fetchOne('SELECT count(1) FROM accounts')); // The account already using the email
    }
    
}
