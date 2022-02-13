<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\FunctionalTests\Monitoring\Controller;

use Kishlin\Tests\Apps\Backoffice\Tools\BackofficeKernelTestCaseTrait;
use Kishlin\Tests\Backend\Apps\AbstractFunctionalTests\Controller\Monitoring\CheckHealthControllerTestCase;

/**
 * Functional Test to verify the application has an up-and-running check-health API endpoint.
 *
 * @see \Kishlin\Tests\Backend\Apps\AbstractFunctionalTests\Controller\Monitoring\CheckHealthControllerTestCase
 *
 * @internal
 * @covers \Kishlin\Apps\Backoffice\Monitoring\Controller\CheckHealthController
 */
final class CheckHealthControllerTest extends CheckHealthControllerTestCase
{
    use BackofficeKernelTestCaseTrait;

    public function testTheAPIShowsStatusForAllServices(): void
    {
        $client      = self::createClient();
        $endpointUri = '/monitoring/check-health';

        $expectedServices = [
            'backoffice',
            'database',
        ];

        self::assertTheAPIShowsStatusForAllServices($client, $endpointUri, $expectedServices);
    }
}
