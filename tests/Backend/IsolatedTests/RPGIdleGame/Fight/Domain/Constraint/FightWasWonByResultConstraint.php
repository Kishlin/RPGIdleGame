<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Fight\Domain\Constraint;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\AbstractFightParticipant;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\Fight;

final class FightWasWonByResultConstraint extends AbstractFightResultConstraint
{
    public function __construct(
        private AbstractFightParticipant $winner
    ) {
    }

    public function toString(): string
    {
        return "was won by {$this->winner->id()->value()}";
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
        return $fight->winnerId()->equals($this->winner->id());
    }
}
