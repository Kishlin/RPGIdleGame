<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Account\Domain\View;

use Kishlin\Backend\Account\Domain\View\AuthenticationDTO;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Account\Domain\View\AuthenticationDTO
 */
final class AuthenticationDTOTest extends TestCase
{
    public function testItCanBeCreatedFromScalars(): void
    {
        $view = AuthenticationDTO::fromScalars('token', 'refreshToken');

        self::assertSame('token', $view->token());
        self::assertSame('refreshToken', $view->refreshToken());
    }
}
