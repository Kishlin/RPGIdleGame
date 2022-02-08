<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\Context;

use Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\HTTPClient\Request;
use PHPUnit\Framework\Assert;

final class AccountContext extends RPGIdleGameAPIContext
{
    protected ?string $accountId = null;

    /**
     * @Given an account already exists with the email
     */
    public function anAccountAlreadyExistsWithTheEmail(): void
    {
        self::database()->exec(
            <<<'SQL'
INSERT INTO accounts (id, username, email, password, salt, is_active)
VALUES ('7d387740-5c15-4712-bdcf-5126c28fc10a', 'User', 'user@example.com', 'password', 'salt', true);

INSERT INTO character_counts (owner_id, character_count, reached_limit)
VALUES ('7d387740-5c15-4712-bdcf-5126c28fc10a', 0, false);
SQL
        );
    }

    /**
     * @When /^a client creates an account$/
     * @When /^a client creates an account with the same email$/
     */
    public function whenAClientCreatesAnAccount(): void
    {
        $response = self::client()->post(new Request(
            uri: '/account/signup',
            headers: [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode('User:password'),
            ],
            params: ['email' => 'user@example.com']
        ));

        $this->accountId    = null;
        $this->responseCode = $response->httpCode();

        if (201 === $response->httpCode()) {
            /** @var array{accountId: string} $decodedBody */
            $decodedBody     = $response->decodedBody();
            $this->accountId = $decodedBody['accountId'];
        }
    }

    /**
     * @Then its credentials are registered
     */
    public function itsCredentialsAreRegistered(): void
    {
        Assert::assertNotNull($this->accountId);
        Assert::assertSame(201, $this->responseCode);

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
     * @Then it did not register the new account
     */
    public function itDidNotRegisterTheNewAccount(): void
    {
        Assert::assertNull($this->accountId);
        Assert::assertSame(409, $this->responseCode);
    }
}
