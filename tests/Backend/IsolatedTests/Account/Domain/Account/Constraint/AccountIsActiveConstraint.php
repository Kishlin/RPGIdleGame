<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Account\Domain\Account\Constraint;

use Kishlin\Backend\Account\Domain\Account;
use Kishlin\Backend\Account\Domain\AccountIsActive;
use Kishlin\Tests\Backend\Tools\ReflectionHelper;
use PHPUnit\Framework\Constraint\Constraint;
use ReflectionException;

final class AccountIsActiveConstraint extends Constraint
{
    public function toString(): string
    {
        return 'is an active account';
    }

    /**
     * @param Account $other
     *
     * @throws ReflectionException
     */
    protected function matches(mixed $other): bool
    {
        $accountIsActive = ReflectionHelper::propertyValue($other, 'accountIsActive');

        if ($accountIsActive instanceof AccountIsActive) {
            return $accountIsActive->value();
        }

        return false;
    }

    /**
     * @param Account $other
     *
     * @throws ReflectionException
     */
    protected function additionalFailureDescription($other): string
    {
        $accountIsActive = ReflectionHelper::propertyValue($other, 'accountIsActive');

        if ($accountIsActive instanceof AccountIsActive) {
            $value = var_export($accountIsActive->value(), true);

            return "Actual status is: {$value}.";
        }

        return 'The accountIsActive property does not have a valid AccountIsAlive object.';
    }

    /**
     * @param Account $other
     */
    protected function failureDescription($other): string
    {
        return 'object of type ' . $other::class . ' ' . $this->toString();
    }
}
