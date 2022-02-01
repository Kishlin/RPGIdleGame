<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Fight\Domain\Constraint;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\Fight;
use PHPUnit\Framework\Constraint\Constraint;

abstract class AbstractFightResultConstraint extends Constraint
{
    /**
     * @noinspection PhpParameterNameChangedDuringInheritanceInspection
     *
     * @param Fight $fight
     *
     * {@inheritDoc}
     */
    protected function failureDescription($fight): string
    {
        return "fight {$fight->id()}" . ' ' . $this->toString();
    }

    /**
     * @noinspection PhpParameterNameChangedDuringInheritanceInspection
     *
     * @param Fight $fight
     *
     * {@inheritDoc}
     */
    protected function additionalFailureDescription($fight): string
    {
        if (null === $fight->winnerId()->value()) {
            return 'It has not recorded any winner.';
        }

        return "It has recorded the winner to be {$fight->winnerId()}.";
    }
}
