<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Test\Isolated\Constraint;

use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\Bus\Event\DomainEvent;
use PHPUnit\Framework\Constraint\Constraint;
use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\Comparator\Factory as ComparatorFactory;

/**
 * Asserts that the AggregateRoot recorded all the expected domain events.
 * The order in which the events were recorded does not matter.
 */
final class AggregateRecordedDomainEventsConstraint extends Constraint
{
    /**
     * @param DomainEvent[] $expectedDomainEvents
     */
    public function __construct(
        private array $expectedDomainEvents
    ) {
    }

    public function toString(): string
    {
        if (empty($this->expectedDomainEvents)) {
            return 'recorded no events';
        }

        $eventsCount = count($this->expectedDomainEvents);
        $eventsList  = implode(', ', array_map(
            static fn (DomainEvent $event) => $event::class,
            $this->expectedDomainEvents,
        ));

        return "recorded {$eventsCount} domain events of types: {$eventsList}";
    }

    /**
     * @param AggregateRoot $other
     */
    protected function matches(mixed $other): bool
    {
        $comparatorFactory = ComparatorFactory::getInstance();
        $domainEvents      = $other->pullDomainEvents();

        try {
            $comparator = $comparatorFactory->getComparatorFor($this->expectedDomainEvents, $domainEvents);

            $comparator->assertEquals($this->expectedDomainEvents, $domainEvents, canonicalize: true);
        } catch (ComparisonFailure $f) {
            return false;
        }

        return true;
    }
}
