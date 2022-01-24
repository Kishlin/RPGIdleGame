<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Account\Domain\Account\ValueObject;

use InvalidArgumentException;
use Kishlin\Backend\Account\Domain\AccountEmail;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Account\Domain\AccountEmail
 */
final class AccountEmailTest extends TestCase
{
    public function testItCanBeCreated(): void
    {
        $validEmail = 'valid.email@example.com';

        self::assertSame($validEmail, (new AccountEmail($validEmail))->value());
    }

    public function testItValidatesValueOnCreation(): void
    {
        $invalidEmail = 'invalid';

        self::expectException(InvalidArgumentException::class);
        new AccountEmail($invalidEmail);
    }
}
