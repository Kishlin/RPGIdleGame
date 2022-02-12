<?php

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\Errors\ExceptionHandlers\RPGIdleGame\Character;

use Kishlin\Apps\RPGIdleGame\Backend\Errors\KernelExceptionHandler;
use Kishlin\Backend\RPGIdleGame\Character\Application\CreateCharacter\HasReachedCharacterLimitException;
use Kishlin\Backend\RPGIdleGame\Character\Application\DeleteCharacter\DeletionIsNotAllowedException;
use Kishlin\Backend\RPGIdleGame\Character\Application\DistributeSkillPoints\CharacterNotFoundException;
use Kishlin\Backend\RPGIdleGame\Character\Domain\NotEnoughSkillPointsException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class CharacterManagementExceptionHandler implements KernelExceptionHandler
{
    public function handle(Throwable $e): ?Response
    {
        if ($e instanceof NotEnoughSkillPointsException) {
            return new JsonResponse('You do not have enough skill points.', Response::HTTP_FORBIDDEN);
        }

        if ($e instanceof HasReachedCharacterLimitException) {
            return new JsonResponse('You cannot create characters anymore.', Response::HTTP_FORBIDDEN);
        }

        if ($e instanceof DeletionIsNotAllowedException || $e instanceof CharacterNotFoundException) {
            return new JsonResponse(status: Response::HTTP_FORBIDDEN);
        }

        return null;
    }
}
