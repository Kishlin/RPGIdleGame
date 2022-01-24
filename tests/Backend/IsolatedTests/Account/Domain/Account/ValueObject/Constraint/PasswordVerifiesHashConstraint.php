<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Account\Domain\Account\ValueObject\Constraint;

use PHPUnit\Framework\Constraint\Constraint;

final class PasswordVerifiesHashConstraint extends Constraint
{
    public function __construct(
        private string $passwordHash
    ) {
    }

    public function toString(): string
    {
        return "verifies password hash {$this->passwordHash}";
    }

    /**
     * @param string $other
     */
    protected function matches(mixed $other): bool
    {
        return password_verify($other, $this->passwordHash);
    }
}
