<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Account\Domain\Account\Constraint;

use Kishlin\Backend\Account\Domain\Account;
use PHPUnit\Framework\Constraint\Constraint;

final class AccountIsActiveConstraint extends Constraint
{
    public function toString(): string
    {
        return 'is an active account';
    }

    /**
     * @param Account $other
     */
    protected function matches(mixed $other): bool
    {
        return $other->accountIsActive()->value();
    }

    /**
     * @param Account $other
     */
    protected function additionalFailureDescription($other): string
    {
        $value = var_export($other->accountIsActive()->value(), true);

        return "Actual status is: {$value}.";
    }

    /**
     * @param Account $other
     */
    protected function failureDescription($other): string
    {
        return 'object of type ' . $other::class . ' ' . $this->toString();
    }
}
