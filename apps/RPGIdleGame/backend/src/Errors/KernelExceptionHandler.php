<?php

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\Errors;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

interface KernelExceptionHandler
{
    public function handle(Throwable $e): ?Response;
}
