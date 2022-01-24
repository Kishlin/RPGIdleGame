<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Test\Isolated;

use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\Bus\Event\DomainEvent;
use Kishlin\Tests\Backend\Tools\Test\Isolated\Constraint\AggregateRecordedDomainEventsConstraint;
use PHPUnit\Framework\TestCase;

/**
 * Abstract TestCase for domain entities, child classes of \Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot.
 *
 * @internal
 * @covers \Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot
 */
abstract class AggregateRootIsolatedTestCase extends TestCase
{
    public static function assertItRecordedDomainEvents(AggregateRoot $root, DomainEvent ...$events): void
    {
        self::assertThat($root, new AggregateRecordedDomainEventsConstraint($events));
    }
}
