<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\Tools;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class SecuredEndpointDrivingTestCase extends WebTestCase
{
    protected const AUTHORIZATION = 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJlMTc4MDBlNzFkN2EiLCJhdWQiOiJlMTc4MDBlNzFkN2EiLCJpYXQiOjE2NDQ1OTIzNTIsInVzZXIiOiJkMTVmYTNlYS1lOTEwLTQ5YmEtOWE3My1kZWJjZjRkN2FmYWQifQ.p15Rpy97dd_t-3hVBVljOBW_bKxIMnjz3_98CkNesAg';

    protected const CLIENT_ID = 'd15fa3ea-e910-49ba-9a73-debcf4d7afad';
}
