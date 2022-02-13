<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\FunctionalTests\Account\Controller;

use Kishlin\Tests\Apps\Backoffice\Tools\BackofficeWebTestCase;

final class AccountsListControllerTest extends BackofficeWebTestCase
{
    public function testItShowsTheListOfAccount(): void
    {
        $client = self::createClient();

        $client->request('GET', '/accounts/all');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('html');

        $this->assertSelectorExists('table#table-accounts');
    }
}
