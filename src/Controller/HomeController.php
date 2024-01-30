<?php
// src/Controller/HomeController.php


namespace App\Controller;

use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PropertyRepository $propertyRepository): Response
    {
        $properties = $propertyRepository->findLatestProperties(9);

        // Ajout de l'information sur l'état de connexion
        $isUserLoggedIn = $this->getUser() !== null;

        return $this->render('home/home.html.twig', [
            'properties' => $properties,
            'isUserLoggedIn' => $isUserLoggedIn,
        ]);
    }
}
