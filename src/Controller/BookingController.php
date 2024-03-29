<?php

// src/Controller/BookingController.php

namespace App\Controller;

use App\Entity\Property;
use App\Form\BookingFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class BookingController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/booking/{pid}', name: 'app_booking')]
    #[IsGranted('ROLE_USER')]
    public function showBookingPage(Request $request, int $pid): Response
    {
        // Récupérer les détails du logement depuis la base de données en utilisant l'identifiant
        $property = $this->entityManager->getRepository(Property::class)->find($pid);

        if (!$property) {
            throw $this->createNotFoundException('Logement non trouvé.');
        }

        // Sauvegarder le prix initial
        $prixInitial = $property->getPrice();

        // Créer une instance du formulaire avec les options du prix initial
        $form = $this->createForm(BookingFormType::class, null, [
            'prix_initial' => $prixInitial,
        ]);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        // Vérifier si le formulaire est soumis et valide
        $isFormValid = $form->isSubmitted() && $form->isValid();

        // Vérifier si les dates sont valides
        $dateArrive = $form->get('DateArrive')->getData();
        $dateDepart = $form->get('DateDepart')->getData();
        $isDatesValid = $this->areDatesValid($dateArrive, $dateDepart);

        // Si le formulaire est soumis, valide, et les dates sont valides, effectuer le calcul du prix
        if ($isFormValid && $isDatesValid) {
            $nights = $this->calculateNights($dateArrive, $dateDepart);
            $newPrix = $this->calculatePrix($prixInitial, $nights);

            // Rediriger vers la page de paiement Stripe avec le nouveau prix
            return $this->redirectToRoute('app_stripe', ['pid' => $pid, 'prix' => $newPrix]);
        }

        // ...

        return $this->render('booking/booking.html.twig', [
            'property' => $property,
            'form' => $form->createView(),
            'isFormValid' => $isFormValid,
            'isDatesValid' => $isDatesValid,
        ]);
    }

    private function areDatesValid(?\DateTimeInterface $dateArrive, ?\DateTimeInterface $dateDepart): bool
    {
        // Vérifiez si les dates sont définies et valides
        if ($dateArrive === null || $dateDepart === null) {
            return false;
        }

        // Vérifiez ici si les dates sont valides selon vos critères
        // Par exemple, si la date d'arrivée est supérieure à aujourd'hui et la date de départ est supérieure à la date d'arrivée
        return $dateArrive >= new \DateTime('today') && $dateDepart > $dateArrive;
    }

    private function calculateNights(\DateTimeInterface $dateArrive, \DateTimeInterface $dateDepart): int
    {
        $interval = $dateArrive->diff($dateDepart);

        return $interval->days;
    }

    private function calculatePrix(string $prixInitial, int $nights): string
    {
        $prixInitialFloat = (float)$prixInitial;
        $totalPrix = $prixInitialFloat * $nights;

        return number_format($totalPrix, 2, '.', ''); // Formatage du prix avec deux décimales
    }
}
