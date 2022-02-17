<?php

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\Errors\ExceptionHandlers\Security;

use Kishlin\Apps\RPGIdleGame\Backend\Errors\KernelExceptionHandler;
use Kishlin\Backend\Shared\Domain\Security\ParsingTokenFailedException;
use Kishlin\Backend\Shared\Infrastructure\Security\Authorization\FailedToReadCookieException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class ParsingTokenFailedExceptionHandler implements KernelExceptionHandler
{
    public function handle(Throwable $e): ?Response
    {
        if ($e instanceof ParsingTokenFailedException || $e instanceof FailedToReadCookieException) {
            return new JsonResponse('Authentication failed.', Response::HTTP_UNAUTHORIZED);
        }

        return null;
    }
}
