<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Apps\AbstractFunctionalTests\Controller\Monitoring\Constraint;

use PHPUnit\Framework\Constraint\Constraint;

final class ServiceStatusIsShowingConstraint extends Constraint
{
    public function __construct(
        private string $service
    ) {
    }

    public function toString(): string
    {
        return "is showing the status of `{$this->service}`";
    }

    /**
     * {@inheritdoc}
     *
     * @param array<string, bool> $data
     *
     * @noinspection PhpParameterNameChangedDuringInheritanceInspection
     */
    protected function matches($data): bool
    {
        return array_key_exists($this->service, $data);
    }

    /**
     * {@inheritdoc}
     *
     * @param array<string, bool> $data
     *
     * @noinspection PhpParameterNameChangedDuringInheritanceInspection
     */
    protected function failureDescription($data): string
    {
        return parent::failureDescription($data);
    }

    /**
     * {@inheritdoc}
     *
     * @param array<string, bool> $data
     *
     * @noinspection PhpParameterNameChangedDuringInheritanceInspection
     */
    protected function additionalFailureDescription($data): string
    {
        $serviceList = implode(', ', array_keys($data));

        return "Listed services: {$serviceList}.";
    }
}
