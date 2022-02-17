<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Account\Domain\View;

use Kishlin\Backend\Account\Domain\View\SimpleAuthenticationDTO;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Account\Domain\View\SimpleAuthenticationDTO
 */
final class SimpleAuthenticationDTOTest extends TestCase
{
    public function testItCanBeCreatedFromScalars(): void
    {
        $view = SimpleAuthenticationDTO::fromScalars('token');

        self::assertSame('token', $view->token());
    }
}
