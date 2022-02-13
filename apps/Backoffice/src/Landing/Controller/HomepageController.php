<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\Landing\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'homepage', methods: [Request::METHOD_GET])]
final class HomepageController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('base.html.twig');
    }
}
