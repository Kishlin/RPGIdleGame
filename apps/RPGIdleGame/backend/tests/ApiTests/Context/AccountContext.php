<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\Context;

use Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\HTTPClient\Request;
use PHPUnit\Framework\Assert;

final class AccountContext extends RPGIdleGameAPIContext
{
    protected ?string $accountId = null;

    private const REFRESH_TOKEN_WITH_NO_EXPIRATION = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJjNmRjM2ZkOTNmNTYiLCJhdWQiOiJjNmRjM2ZkOTNmNTYiLCJpYXQiOjE2NDQ0MTUxNjUsInVzZXIiOiI0OWFlMzQxZi0yN2RjLTQxNGQtYjM5OS1iYmYxZmIxNDQyMmEiLCJzYWx0IjoiMWM5Y2YzZDM1YzZhMmFhYTY4NWEzOWRmY2JmMzRlNGRjMzgxMzJiNCJ9.2uqgwiPboko5KlYwJFrwbzaIGBBhLnKDRrXkd7WQN-E';

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
            params: ['email' => 'user@example.com']
        ));;
    }

    /**
     * @Then its credentials are registered
     */
    public function itsCredentialsAreRegistered(): void
    {
        Assert::assertNotNull($this->accountId);

        $count = self::database()->fetchOne('SELECT count(1) FROM accounts WHERE id = :id', ['id' => $this->accountId]);

        Assert::assertSame(1, $count);
    }

    /**
     * @Then a fresh character count is registered
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
     * @Then it did not register the new account
     */
    public function itDidNotRegisterTheNewAccount(): void
    {
        Assert::assertNull($this->accountId);
        Assert::assertSame(409, $this->response->httpCode());
        Assert::assertSame(1, self::database()->fetchOne('SELECT count(1) FROM accounts')); // The account already using the email
    }
    
}
