<?php
// src/Controller/StripeController.php

namespace App\Controller;

use App\Entity\Hebergement;
use App\Entity\Property;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Stripe;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;


class StripeController extends AbstractController
{
    #[Route('/stripe/{pid}', name: 'app_stripe')]
    public function index(Request $request, int $pid, PersistenceManagerRegistry $doctrine): Response
    {
        $property = $doctrine->getRepository(Property::class)->find($pid);
    
        if (!$property) {
            throw $this->createNotFoundException('Logement non trouvé.');
        }
    
        $prix = $property->getPrice();
    
        return $this->render('stripe/index.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
            'prix' => $prix,
            'pid' => $pid,
            'property' => $property
        ]);
    }
    
    #[Route('/stripe/create-charge/{pid}', name: 'app_stripe_charge', methods: ['POST'])]
    public function createCharge(Request $request, EntityManagerInterface $entityManager, PersistenceManagerRegistry $doctrine)
    {
        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
    
        $prix = $request->get('prix');
        $dateArrive = $request->get('dateArrive');
        $dateDepart = $request->get('dateDepart');
    
        // Créer une nouvelle entité Hebergement
        $hebergement = new Hebergement();
        $hebergement->setPrix($prix);
        $hebergement->setPaiementEffectue(true);
        $hebergement->setDateArrive(new \DateTime($dateArrive));
        $hebergement->setDateDepart(new \DateTime($dateDepart));
    
        $propertyId = $request->get('pid');
        $property = $doctrine->getRepository(Property::class)->find($propertyId);
    
        if (!$property) {
            throw $this->createNotFoundException('Logement non trouvé.');
        }
    
        $prix_initial_property = $property->getPrice();
        $hebergement->setPrixInitial($prix_initial_property);
    
        $entityManager->persist($hebergement);
        $entityManager->flush();
    
        Stripe\Charge::create([
            "amount" => $prix * 100,
            "currency" => "eur",
            "source" => $request->request->get('stripeToken'),
            "description" => "atypikhouse Payment"
        ]);
    
        $this->addFlash(
            'success',
            'Paiement réussi!'
        );
    
        return $this->redirectToRoute('app_home');
    }
}    