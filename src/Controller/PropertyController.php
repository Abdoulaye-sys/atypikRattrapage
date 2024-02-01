<?php

namespace App\Controller;

use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    #[Route('/property', name: 'app_property')]
    public function index(PropertyRepository $propertyRepository): Response
    {
        $properties = $propertyRepository->findLatestProperties(9);
        return $this->render('property/property.html.twig', [
            'properties' => $properties,
            'controller_name' => 'PropertyController',
        ]);
    }
}
