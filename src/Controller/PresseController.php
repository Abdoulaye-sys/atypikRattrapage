<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PresseController extends AbstractController
{
    #[Route('/presse', name: 'app_presse')]
    public function index(): Response
    {
        return $this->render('presse/presse.html.twig', [
            'controller_name' => 'PresseController',
        ]);
    }
}
