<?php
// src/Controller/StripeController.php

namespace App\Controller;

use App\Entity\Property;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Stripe;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;


class StripeController extends AbstractController
{
    #[Route('/stripe/{pid}', name: 'app_stripe')]
    public function index(Request $request, int $pid, PersistenceManagerRegistry $doctrine): Response
    {
        // Récupérer les détails du logement depuis la base de données en utilisant l'identifiant
        $property = $doctrine->getRepository(Property::class)->find($pid);

        if (!$property) {
            throw $this->createNotFoundException('Logement non trouvé.');
        }

        // Récupérer le prix depuis l'entité Property
        $prix = $property->getPrice();

        return $this->render('stripe/index.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
            'prix' => $prix,
        ]);
    }

    #[Route('/stripe/create-charge', name: 'app_stripe_charge', methods: ['POST'])]
    public function createCharge(Request $request)
    {
        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);

        // Récupérer le prix depuis le formulaire
        $prix = $request->get('prix');

        Stripe\Charge::create([
            "amount" => $prix * 100,
            "currency" => "eur",
            "source" => $request->request->get('stripeToken'),
            "description" => "atypikhouse Payment"
        ]);

        $this->addFlash(
            'success',
            'Payment Successful!'
        );

        return $this->redirectToRoute('app_stripe', ['pid' => $request->get('pid')], Response::HTTP_SEE_OTHER);
    }
}
