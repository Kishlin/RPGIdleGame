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
    public const CHARACTERS_QUERY = <<<'SQL'
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
    public function __invoke(Request $request): Response
    {
        $query = self::CHARACTERS_QUERY;

        if (
            $request->query->has('order')
            && $request->query->has('dir')
            && in_array($request->query->get('dir'), ['ASC', 'DESC'], true)
            && in_array($request->query->get('order'), ['skill_points', 'rank', 'fights_count'], true)
        ) {
            $query .= ' ORDER BY ' . $request->query->get('order') . ' ' . $request->query->get('dir');
        }

        $characters = $this->entityManager->getConnection()->fetchAllAssociative($query);
        assert(false !== $characters);

        return $this->render('pages/characters/all.html.twig', [
            'characters' => $characters,
        ]);
    }
}
