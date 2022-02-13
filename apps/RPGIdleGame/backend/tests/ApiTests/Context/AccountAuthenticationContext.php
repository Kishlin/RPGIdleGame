<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\Context;

use Kishlin\Tests\Apps\RPGIdleGame\Backend\Tools\HTTPClient\Request;
use PHPUnit\Framework\Assert;

final class AccountAuthenticationContext extends RPGIdleGameAPIContext
{
    private const REFRESH_TOKEN_WITH_NO_EXPIRATION = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJjNmRjM2ZkOTNmNTYiLCJhdWQiOiJjNmRjM2ZkOTNmNTYiLCJpYXQiOjE2NDQ0MTczNDYsInVzZXIiOiI3ZDM4Nzc0MC01YzE1LTQ3MTItYmRjZi01MTI2YzI4ZmMxMGEiLCJzYWx0IjoiMWM5Y2YzZDM1YzZhMmFhYTY4NWEzOWRmY2JmMzRlNGRjMzgxMzJiNCJ9.4lfYorjgyPRFtvCe7OvSffgycSm_vPO0swxR5wmonh4';
    private const REFRESH_TOKEN_EXPIRED            = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJjNmRjM2ZkOTNmNTYiLCJhdWQiOiJjNmRjM2ZkOTNmNTYiLCJpYXQiOjE2NDQ0MTczNDYsInVzZXIiOiI3ZDM4Nzc0MC01YzE1LTQ3MTItYmRjZi01MTI2YzI4ZmMxMGEiLCJleHAiOjE2NDQ0MTczNDYsInNhbHQiOiIxYzljZjNkMzVjNmEyYWFhNjg1YTM5ZGZjYmYzNGU0ZGMzODEzMmI0In0.B4Oayevt44xPOJQJN30kmFnVmd630P1dGTz6Cwfzwas';

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
        ));
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
        ));
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
        ));
    }

    /**
     * @Then /^the authentication was authorized$/
     */
    public function theAuthenticationWasAuthorized(): void
    {
        Assert::assertNotNull($this->response);
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
        Assert::assertNotNull($this->response);
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
        Assert::assertNotNull($this->response);
        Assert::assertSame(401, $this->response->httpCode());
    }
}
