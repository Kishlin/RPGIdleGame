<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Domain\Aggregate;

use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\Bus\Event\DomainEvent;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\ReflectionHelper;
use PHPUnit\Framework\TestCase;
use ReflectionException;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot
 */
final class AggregateRootTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testItCanRecordAndPullEvents(): void
    {
        $root = new class() extends AggregateRoot {};
        $uuid = new class('51cefa3e-c223-469e-a23c-61a32e4bf048') extends UuidValueObject {};

        $event = new class($uuid) extends DomainEvent {};

        self::assertEmpty($root->pullDomainEvents());

        ReflectionHelper::invoke($root, 'record', $event);
        self::assertContainsEquals($event, $root->pullDomainEvents());
        self::assertEmpty($root->pullDomainEvents());
    }
}
