<?php

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\Errors\ExceptionHandlers\RPGIdleGame\Fight;

use Kishlin\Apps\RPGIdleGame\Backend\Errors\KernelExceptionHandler;
use Kishlin\Backend\RPGIdleGame\Fight\Application\InitiateAFight\RequesterIsNotAllowedToInitiateFight;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\NoOpponentAvailableException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class FightExceptionHandler implements KernelExceptionHandler
{
    public function handle(Throwable $e): ?Response
    {
        if ($e instanceof RequesterIsNotAllowedToInitiateFight) {
            return new JsonResponse(status: Response::HTTP_FORBIDDEN);
        }

        if ($e instanceof NoOpponentAvailableException) {
            return new JsonResponse('No opponent available.', status: Response::HTTP_NOT_FOUND);
        }

        return null;
    }
}
