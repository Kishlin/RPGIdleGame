<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Domain\Bus\Message\Constraint;

use PHPUnit\Framework\Constraint\Constraint;

/**
 * Asserts that base value was mapped properly, to the expected type and value.
 */
final class ItMapsTheValueCorrectlyConstraint extends Constraint
{
    public function __construct(
        private int|bool|string $expectedTypedValue
    ) {
    }

    public function toString(): string
    {
        $expectedType  = gettype($this->expectedTypedValue);
        $expectedValue = $this->mixedToString($this->expectedTypedValue);

        return "equals {$expectedValue} and is of type {$expectedType}";
    }

    /**
     * @param bool|int|string $other
     */
    protected function matches(mixed $other): bool
    {
        return $other === $this->expectedTypedValue;
    }

    /**
     * @param bool|int|string $other
     */
    protected function additionalFailureDescription(mixed $other): string
    {
        $actualType  = gettype($other);
        $actualValue = $this->mixedToString($other);

        return "Got {$actualValue} of type {$actualType}.";
    }

    /**
     * @param bool|int|string $value
     */
    private function mixedToString(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return (string) $value;
    }
}
