<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Test\Isolated\Constraint;

use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\Bus\Event\DomainEvent;
use PHPUnit\Framework\Constraint\Constraint;
use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\Comparator\Factory as ComparatorFactory;
use SebastianBergmann\Exporter\Exporter;

/**
 * Asserts that the AggregateRoot recorded all the expected domain events.
 * The order in which the events were recorded does not matter.
 */
final class AggregateRecordedDomainEventsConstraint extends Constraint
{
    /**
     * @var DomainEvent[]
     */
    private array $actualEvents = [];

    private ?Exporter $exporterToUse = null;

    /**
     * @param DomainEvent[]               $expectedDomainEvents
     * @param null|class-string<Exporter> $exporterToUseClass
     */
    public function __construct(
        private array $expectedDomainEvents,
        private ?string $exporterToUseClass = null
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

    protected function exporter(): Exporter
    {
        if (null !== $this->exporterToUseClass && null === $this->exporterToUse) {
            $this->exporterToUse = new $this->exporterToUseClass();
        }

        return $this->exporterToUse ?? parent::exporter();
    }

    /**
     * @param AggregateRoot $other
     */
    protected function matches(mixed $other): bool
    {
        $comparatorFactory  = ComparatorFactory::getInstance();
        $this->actualEvents = $other->pullDomainEvents();

        try {
            $comparator = $comparatorFactory->getComparatorFor($this->expectedDomainEvents, $this->actualEvents);

            $comparator->assertEquals($this->expectedDomainEvents, $this->actualEvents, canonicalize: true);
        } catch (ComparisonFailure $f) {
            return false;
        }

        return true;
    }

    protected function additionalFailureDescription($other): string
    {
        $actual   = $this->exporter()->export($this->actualEvents);
        $expected = $this->exporter()->export($this->expectedDomainEvents);

        return "Found events to be: {$actual} when it expected: {$expected}";
    }
}
