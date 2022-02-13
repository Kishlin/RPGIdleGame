<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\Account\Controller;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'accounts_all', methods: [Request::METHOD_GET])]
final class AccountsListController extends AbstractController
{
    const ACCOUNTS_QUERY = <<<'SQL'
SELECT accounts.*, character_counts.character_count 
FROM accounts 
LEFT JOIN character_counts ON character_counts.owner_id = accounts.id
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
        $accounts = $this->entityManager->getConnection()->fetchAllAssociative(self::ACCOUNTS_QUERY);
        assert(false !== $accounts);

        return $this->render('pages/accounts/all.html.twig', [
            'accounts' => $accounts,
        ]);
    }
}
