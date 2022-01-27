<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\RPGIdleGame\Character\Application\DistributeSkillPoints\CharacterNotFoundException;
use Kishlin\Backend\RPGIdleGame\Character\Domain\CharacterViewer;
use Kishlin\Backend\RPGIdleGame\Character\Domain\View\CompleteCharacterView;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineViewer;

class CharacterViewerUsingDoctrine extends DoctrineViewer implements CharacterViewer
{
    /**
     * @throws CharacterNotFoundException|Exception
     */
    public function viewOneById(string $characterId): CompleteCharacterView
    {
        /** @var array<string, int|string>|false $data */
        $data = $this->entityManager->getConnection()->fetchAssociative(
            'SELECT * from characters WHERE character_id = :id',
            ['id' => $characterId],
        );

        if (false === $data) {
            throw new CharacterNotFoundException();
        }

        return CompleteCharacterView::fromSource($data);
    }
}
