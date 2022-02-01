<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Fight\Domain\Constraint;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\Fight;

final class FightIsADrawResultConstraint extends AbstractFightResultConstraint
{
    public function toString(): string
    {
        return 'is a draw';
    }

    /**
     * @noinspection PhpParameterNameChangedDuringInheritanceInspection
     *
     * @param Fight $fight
     *
     * {@inheritDoc}
     */
    protected function matches($fight): bool
    {
        return null === $fight->winnerId()->value();
    }
}
