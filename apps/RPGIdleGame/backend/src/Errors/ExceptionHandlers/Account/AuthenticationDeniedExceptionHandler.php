<?php

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\Errors\ExceptionHandlers\Account;

use Kishlin\Apps\RPGIdleGame\Backend\Errors\KernelExceptionHandler;
use Kishlin\Backend\Account\Application\Authenticate\AuthenticationDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class AuthenticationDeniedExceptionHandler implements KernelExceptionHandler
{
    public function handle(Throwable $e): ?Response
    {
        if ($e instanceof AuthenticationDeniedException) {
            return new JsonResponse('Authentication failed.', Response::HTTP_UNAUTHORIZED);
        }

        return null;
    }
}
