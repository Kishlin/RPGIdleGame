<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\Tools;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class RPGIdleGameWebTestCase extends WebTestCase
{
    use RPGIdleGameKernelTestCaseTrait;
}
