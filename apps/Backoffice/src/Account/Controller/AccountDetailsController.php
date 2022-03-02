<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\Account\Controller;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/{id}',
    name: 'accounts_one',
    requirements: ['id' => '[\w\-]+'],
    methods: [Request::METHOD_GET],
)]
final class AccountDetailsController extends AbstractController
{
    public const ACCOUNT_QUERY = <<<'SQL'
SELECT accounts.id, accounts.username, accounts.email, accounts.is_active, character_counts.character_count
FROM accounts
LEFT JOIN character_counts ON character_counts.owner_id = accounts.id
WHERE accounts.id = :id
LIMIT 1
SQL;

    public const CHARACTERS_QUERY = <<<'SQL'
SELECT characters.id, characters.name, characters.skill_points, characters.rank, characters.fights_count, characters.wins_count, characters.draws_count, characters.losses_count, accounts.username as owner
FROM characters
LEFT JOIN accounts ON characters.owner = accounts.id
WHERE characters.owner = :owner
SQL;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(Request $request, string $id): Response
    {
        $account = $this->entityManager->getConnection()->fetchAssociative(self::ACCOUNT_QUERY, ['id' => $id]);

        if (empty($account)) {
            return new Response(status: Response::HTTP_NOT_FOUND);
        }

        $query = $this->charactersQuery($request, $id);

        $characters = $this->entityManager->getConnection()->fetchAllAssociative($query, ['owner' => $id]);

        return $this->render('pages/accounts/one.html.twig', [
            'account'    => $account,
            'characters' => $characters,
        ]);
    }

    private function charactersQuery(Request $request, string $id): string
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

        return $query;
    }
}
