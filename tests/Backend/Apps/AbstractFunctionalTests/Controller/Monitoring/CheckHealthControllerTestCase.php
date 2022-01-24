<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Apps\AbstractFunctionalTests\Controller\Monitoring;

use Kishlin\Tests\Backend\Apps\AbstractFunctionalTests\Controller\Monitoring\Constraint\ServiceStatusIsShowingConstraint;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Functional Test to verify the application has an up-and-running check-health API endpoint.
 *
 * @internal
 * @coversNothing
 */
abstract class CheckHealthControllerTestCase extends WebTestCase
{
    /**
     * @param array<string, bool> $data
     */
    public static function assertDataShowsStatusForService(array $data, string $service, string $message = ''): void
    {
        self::assertThat($data, new ServiceStatusIsShowingConstraint($service), $message);
    }

    /**
     * @param string[] $expectedServices
     */
    protected function assertTheAPIShowsStatusForAllServices(
        KernelBrowser $client,
        string $endpointUri,
        array $expectedServices = []
    ): void {
        $client->request('GET', $endpointUri);

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $content = $client->getResponse()->getContent();

        if (false === $content) {
            self::fail('Failed to retrieve any content from the response.');
        }

        /** @var array<string, bool> $data */
        $data = json_decode($content, true);

        foreach ($expectedServices as $service) {
            $this->assertDataShowsStatusForService($data, $service);
        }
    }
}
