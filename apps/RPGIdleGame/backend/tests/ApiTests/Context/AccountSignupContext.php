<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\Context;

use Kishlin\Tests\Apps\RPGIdleGame\Backend\Tools\HTTPClient\Request;
use PHPUnit\Framework\Assert;

final class AccountSignupContext extends RPGIdleGameAPIContext
{
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
    }

    /**
     * @Then /^its credentials are registered$/
     */
    public function itsCredentialsAreRegistered(): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertSame(201, $this->response->httpCode());

        Assert::assertCount(2, $this->response->cookies());

        $cookiesText = implode(' ', $this->response->cookies());

        Assert::assertNotFalse(strpos($cookiesText, 'token'));
        Assert::assertNotFalse(strpos($cookiesText, 'refreshToken'));

        Assert::assertSame(1, self::database()->fetchOne('SELECT count(1) FROM accounts'));
    }

    /**
     * @Then /^a fresh character count is registered$/
     */
    public function aFreshCharacterCountIsRegistered(): void
    {
        Assert::assertSame(1, self::database()->fetchOne('SELECT count(1) FROM character_counts'));
    }

    /**
     * @Then /^it did not register the new account$/
     */
    public function itDidNotRegisterTheNewAccount(): void
    {
        Assert::assertNotNull($this->response);
        Assert::assertSame(409, $this->response->httpCode());
        Assert::assertEmpty($this->response->cookies());

        Assert::assertSame(1, self::database()->fetchOne('SELECT count(1) FROM accounts')); // The account already using the email
    }
}
