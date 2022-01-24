<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Account\Domain\Account\ValueObject;

use Kishlin\Backend\Account\Domain\AccountPassword;
use Kishlin\Tests\Backend\IsolatedTests\Account\Domain\Account\ValueObject\Constraint\PasswordVerifiesHashConstraint;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Account\Domain\AccountPassword
 */
final class AccountPasswordTest extends TestCase
{
    public function testItHashesTheGivenValue(): void
    {
        $password    = 'password';
        $valueObject = new AccountPassword($password);

        self::assertPasswordIsVerifiedByHash($password, $valueObject->value());
    }

    public static function assertPasswordIsVerifiedByHash(string $password, string $hashToTest): void
    {
        self::assertThat($password, new PasswordVerifiesHashConstraint($hashToTest));
    }
}
