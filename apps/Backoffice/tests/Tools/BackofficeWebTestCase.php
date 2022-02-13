<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\Tools;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class BackofficeWebTestCase extends WebTestCase
{
    use BackofficeKernelTestCaseTrait;
}
