<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\Tools;

use Kishlin\Apps\RPGIdleGame\Backend\RPGIdleGameKernel;

/**
 * Any TestCase willing to use the Symfony Kernel should use this trait.
 * This trait will ensure Symfony uses the correct Kernel class for tests.
 */
trait RPGIdleGameKernelTestCaseTrait
{
    /**
     * @see \Symfony\Bundle\FrameworkBundle\Test\KernelTestCase::$class
     *
     * This field is used as a memory for Symfony, to know which kernel to boot.
     * The behavior prevents tests from different projects to run one after the other,
     *     as the second application will use the kernel from the first application if it was booted at least one.
     * By manually erasing the memory, we make sure each application test will boot the correct Kernel.
     */
    public static function tearDownAfterClass(): void
    {
        static::$class = null;
    }

    protected static function getKernelClass(): string
    {
        return RPGIdleGameKernel::class;
    }
}
