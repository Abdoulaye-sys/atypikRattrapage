<?php
// src/Controller/PropertyDetailsController.php

namespace App\Controller;

use App\Entity\Property;  // Assurez-vous d'importer l'entité Property
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

class PropertyDetailsController extends AbstractController
{
    #[Route('/property/{id}', name: 'app_property_details')]
    public function showDetails($id, PersistenceManagerRegistry $doctrine): Response
    {
        // Récupérer l'entité Property correspondante à l'ID
        $property = $doctrine->getRepository(Property::class)->find($id);

        // Vérifier si la propriété existe
        if (!$property) {
            throw $this->createNotFoundException('Propriété non trouvée');
        }

        // Rendre la vue avec les détails de la propriété
        return $this->render('property_details/details.html.twig', [
            'property' => $property,
        ]);
    }
}
