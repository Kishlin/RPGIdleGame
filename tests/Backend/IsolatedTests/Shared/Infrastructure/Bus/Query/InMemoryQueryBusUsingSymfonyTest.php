<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Infrastructure\Bus\Query;

use Kishlin\Backend\Shared\Domain\Bus\Query\Query;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;
use Kishlin\Backend\Shared\Infrastructure\Bus\Query\InMemoryQueryBusUsingSymfony;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Throwable;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Infrastructure\Bus\Query\InMemoryQueryBusUsingSymfony
 */
final class InMemoryQueryBusUsingSymfonyTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function testItAsksAQueryAndReturnsTheResponse(): void
    {
        $query    = new class() implements Query {};
        $response = new class() implements Response {};

        $symfonyMessageBus = $this->getMockBuilder(MessageBus::class)->disableOriginalConstructor()->getMock();
        $symfonyMessageBus->expects(self::once())->method('dispatch')->with($query)->willReturn(
            new Envelope($query, [new HandledStamp($response, 'handler')])
        );

        $inMemoryQueryBusUsingSymfony = new InMemoryQueryBusUsingSymfony($symfonyMessageBus);

        self::assertSame($response, $inMemoryQueryBusUsingSymfony->ask($query));
    }
}
