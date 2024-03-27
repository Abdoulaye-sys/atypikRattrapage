<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DevenezPartenaireController extends AbstractController
{
    #[Route('/devenez/partenaire', name: 'app_devenez_partenaire')]
    public function index(): Response
    {
        return $this->render('devenez_partenaire/partenaire.html.twig', [
            'controller_name' => 'DevenezPartenaireController',
        ]);
    }
}
