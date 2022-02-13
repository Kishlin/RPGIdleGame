<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\RPGIdleGame\Character\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/all', name: 'characters_all', methods: [Request::METHOD_GET])]
final class CharactersListController extends AbstractController
{
    const CHARACTERS_QUERY = <<<'SQL'
SELECT characters.name, characters.skill_points, characters.rank, characters.fights_count, characters.wins_count, characters.draws_count, characters.losses_count, accounts.username as owner 
FROM characters 
LEFT JOIN accounts ON characters.owner = accounts.id
SQL;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(): Response
    {
        $characters = $this->entityManager->getConnection()->fetchAllAssociative(self::CHARACTERS_QUERY);
        assert(false !== $characters);

        return $this->render('characters/all.html.twig', [
            'characters' => $characters,
        ]);
    }
}
