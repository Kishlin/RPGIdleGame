<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\FunctionalTests\Account\Controller;

use Kishlin\Tests\Apps\Backoffice\Tools\BackofficeWebTestCase;
use Kishlin\Tests\Backend\Tools\Provider\AccountProvider;

/**
 * @internal
 * @covers \Kishlin\Apps\Backoffice\Account\Controller\AccountDetailsController
 */
final class AccountDetailsControllerTest extends BackofficeWebTestCase
{
    public function testItShowsTheDetailsOfOneAccount(): void
    {
        $account = AccountProvider::activeAccount();

        self::loadFixtures($account);

        $client = self::createClient();

        $client->request('GET', '/accounts/' . $account->id()->value());

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('html');

        $this->assertSelectorExists('dl#definition-account');
        $this->assertSelectorExists('table#table-characters');
    }
}
