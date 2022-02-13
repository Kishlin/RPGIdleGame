<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\FunctionalTests\RPGIdleGame\Fight\Controller;

use Kishlin\Tests\Apps\Backoffice\Tools\BackofficeWebTestCase;

final class FightsListControllerTest extends BackofficeWebTestCase
{
    public function testItShowsTheListOfCharacters(): void
    {
        $client = self::createClient();

        $client->request('GET', '/fights/all');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('html');

        $this->assertSelectorExists('table#table-fights');
    }
}
