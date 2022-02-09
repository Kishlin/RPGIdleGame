<?php

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\Errors\ExceptionHandlers\Account;

use Kishlin\Apps\RPGIdleGame\Backend\Errors\KernelExceptionHandler;
use Kishlin\Backend\Account\Application\Authenticate\AuthenticationDeniedException;
use Kishlin\Backend\Account\Application\RefreshAuthentication\CannotRefreshAuthenticationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class AuthenticationFailureHandler implements KernelExceptionHandler
{
    public function handle(Throwable $e): ?Response
    {
        if ($e instanceof AuthenticationDeniedException || $e instanceof CannotRefreshAuthenticationException) {
            return new JsonResponse('Authentication failed.', Response::HTTP_UNAUTHORIZED);
        }

        return null;
    }
}
