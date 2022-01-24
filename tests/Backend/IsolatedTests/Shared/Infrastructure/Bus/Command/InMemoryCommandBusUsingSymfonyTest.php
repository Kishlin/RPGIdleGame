<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Infrastructure\Bus\Command;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Infrastructure\Bus\Command\InMemoryCommandBusUsingSymfony;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Throwable;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Infrastructure\Bus\Command\InMemoryCommandBusUsingSymfony
 */
final class InMemoryCommandBusUsingSymfonyTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function testItExecutesACommandAndReturnsTheResult(): void
    {
        $command = new class() implements Command {};
        $result  = new class() {};

        $symfonyMessageBus = $this->getMockBuilder(MessageBus::class)->disableOriginalConstructor()->getMock();
        $symfonyMessageBus->expects(self::once())->method('dispatch')->with($command)->willReturn(
            new Envelope($command, [new HandledStamp($result, 'handler')])
        );

        $inMemoryCommandBusUsingSymfony = new InMemoryCommandBusUsingSymfony($symfonyMessageBus);

        self::assertSame($result, $inMemoryCommandBusUsingSymfony->execute($command));
    }
}
