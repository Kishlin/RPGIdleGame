<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Monitoring\Controller\Symfony;

use Kishlin\Backend\Shared\Infrastructure\Monitoring\Probe\Probe;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractCheckHealthController
{
    /** @var Probe[] */
    private iterable $probes;

    /**
     * @param Probe[] $probes
     */
    public function __construct(
        #[TaggedIterator('kishlin.shared.infrastructure.monitoring.probe')] iterable $probes
    ) {
        $this->probes = $probes;
    }

    public function __invoke(): Response
    {
        $data = [];

        foreach ($this->probes as $probe) {
            $data[$probe->name()] = $probe->isAlive();
        }

        return new JsonResponse($data);
    }
}
