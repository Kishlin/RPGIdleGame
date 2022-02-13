<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\FunctionalTests\RPGIdleGame\Character\Controller;

use Kishlin\Tests\Apps\Backoffice\Tools\BackofficeWebTestCase;

final class CharactersListControllerTest extends BackofficeWebTestCase
{
    public function testItShowsTheListOfCharacters(): void
    {
        $client = self::createClient();

        $client->request('GET', '/characters/all');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('html');

        $this->assertSelectorExists('table#table-characters');
    }
}
