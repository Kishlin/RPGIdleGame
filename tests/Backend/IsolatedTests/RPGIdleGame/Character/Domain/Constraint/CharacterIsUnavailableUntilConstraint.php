<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Character\Domain\Constraint;

use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
use Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Character\Exporter\CharacterExporter;
use PHPUnit\Framework\Constraint\Constraint;
use SebastianBergmann\Exporter\Exporter;

final class CharacterIsUnavailableUntilConstraint extends Constraint
{
    public const FORMAT = 'Y-m-d H:i:s';

    private ?CharacterExporter $characterExporter = null;

    public function __construct(
        private string $expectedDate,
    ) {
    }

    public function toString(): string
    {
        return 'is unavailable until ' . $this->expectedDate;
    }

    /**
     * @noinspection PhpParameterNameChangedDuringInheritanceInspection
     *
     * @param Character $character
     *
     * {@inheritDoc}
     */
    protected function matches($character): bool
    {
        return $this->expectedDate === $this->actual($character);
    }

    /**
     * @noinspection PhpParameterNameChangedDuringInheritanceInspection
     *
     * @param Character $character
     *
     * {@inheritDoc}
     */
    protected function additionalFailureDescription($character): string
    {
        return 'Found character to be unavailable until ' . $this->actual($character);
    }

    protected function exporter(): Exporter
    {
        if (null === $this->characterExporter) {
            $this->characterExporter = new CharacterExporter();
        }

        return $this->characterExporter;
    }

    private function actual(Character $character): string
    {
        return $character->availability()->value()->format(self::FORMAT);
    }
}
