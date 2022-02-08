<?php

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\Errors\ExceptionHandlers\Account;

use Kishlin\Apps\RPGIdleGame\Backend\Errors\KernelExceptionHandler;
use Kishlin\Backend\Account\Application\Signup\AnAccountAlreadyUsesTheEmailException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class AnAccountAlreadyUsesTheEmailExceptionHandler implements KernelExceptionHandler
{
    public function handle(Throwable $e): ?Response
    {
        if ($e instanceof AnAccountAlreadyUsesTheEmailException) {
            return new JsonResponse('Unable to create account.', Response::HTTP_CONFLICT);
        }

        return null;
    }
}
