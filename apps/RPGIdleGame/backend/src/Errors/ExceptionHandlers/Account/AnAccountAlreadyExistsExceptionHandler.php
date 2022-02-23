<?php

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\Errors\ExceptionHandlers\Account;

use Kishlin\Apps\RPGIdleGame\Backend\Errors\KernelExceptionHandler;
use Kishlin\Backend\Account\Application\Signup\AnAccountAlreadyExistsException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class AnAccountAlreadyExistsExceptionHandler implements KernelExceptionHandler
{
    public function handle(Throwable $e): ?Response
    {
        if ($e instanceof AnAccountAlreadyExistsException) {
            $data = ['field' => $e->conflictField()];

            return new JsonResponse($data, Response::HTTP_CONFLICT);
        }

        return null;
    }
}
