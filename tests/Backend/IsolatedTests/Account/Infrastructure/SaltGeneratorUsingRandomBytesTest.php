<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Account\Infrastructure;

use Exception;
use Kishlin\Backend\Account\Infrastructure\SaltGeneratorUsingRandomBytes;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class SaltGeneratorUsingRandomBytesTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testItCanCreateARandomString(): void
    {
        $generator = new SaltGeneratorUsingRandomBytes();

        self::assertIsString($generator->salt());
    }
}
