<?php

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\Errors\ExceptionHandlers\RPGIdleGame\Character;

use Kishlin\Apps\RPGIdleGame\Backend\Errors\KernelExceptionHandler;
use Kishlin\Backend\RPGIdleGame\Character\Application\CreateCharacter\HasReachedCharacterLimitException;
use Kishlin\Backend\RPGIdleGame\Character\Application\DeleteCharacter\DeletionIsNotAllowedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class CharacterManagementExceptionHandler implements KernelExceptionHandler
{
    public function handle(Throwable $e): ?Response
    {
        if ($e instanceof HasReachedCharacterLimitException) {
            return new JsonResponse('You cannot create characters anymore.', Response::HTTP_FORBIDDEN);
        }

        if ($e instanceof DeletionIsNotAllowedException) {
            return new JsonResponse(status: Response::HTTP_FORBIDDEN);
        }

        return null;
    }
}
