<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\RPGIdleGame\Fight\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/all', name: 'fights_all', methods: [Request::METHOD_GET])]
final class FightsListController extends AbstractController
{
    const FIGHTS_QUERY = <<<'SQL'
SELECT fights.id, winners.name as winner_name, initiators.name as initiator_name, fight_initiators.rank as initiator_rank, opponents.name as opponent_name, fight_opponents.rank as opponent_rank, count(fight_turns.id) as turns_count
FROM fights
LEFT JOIN fight_initiators ON fight_initiators.id = fights.initiator
LEFT JOIN fight_opponents ON fight_opponents.id = fights.opponent
LEFT JOIN characters opponents ON opponents.id = fight_opponents.character_id
LEFT JOIN characters initiators ON initiators.id = fight_initiators.character_id
LEFT JOIN characters winners ON winners.id = fights.winner_id
LEFT JOIN fight_turns on fight_turns.fight_id = fights.id
GROUP BY fights.id, winners.name, initiators.name, fight_initiators.rank, opponents.name, fight_opponents.rank
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
        $query = self::FIGHTS_QUERY;

        if (
            $request->query->has('order')
            && $request->query->has('dir')
            && in_array($request->query->get('dir'), ['ASC', 'DESC'])
            && in_array($request->query->get('order'), ['turns_count', 'fight_opponents.rank', 'fight_initiators.rank'])
        ) {
            $query .= ' ORDER BY ' . $request->query->get('order') . ' ' . $request->query->get('dir');
        }

        $fights = $this->entityManager->getConnection()->fetchAllAssociative($query);
        assert(false !== $fights);

        return $this->render('pages/fights/all.html.twig', [
            'fights' => $fights,
        ]);
    }
}
