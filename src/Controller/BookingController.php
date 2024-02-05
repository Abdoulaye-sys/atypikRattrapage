<?php

namespace App\Controller;

use App\Entity\Property;
use App\Form\BookingFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/booking/{pid}', name: 'app_booking')]
    public function showBookingPage(Request $request, int $pid): Response
    {
        // Récupérer les détails du logement depuis la base de données en utilisant l'identifiant
        $property = $this->entityManager->getRepository(Property::class)->find($pid);

        if (!$property) {
            throw $this->createNotFoundException('Logement non trouvé.');
        }

        // Créer une instance du formulaire avec les options du prix initial
        $form = $this->createForm(BookingFormType::class, null, [
            'prix_initial' => $property->getPrice(), // Ajoutez ici le prix initial
        ]);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        // Vérifier si le formulaire est soumis et valide
        $isFormValid = $form->isSubmitted() && $form->isValid();

        // Si le formulaire est soumis et valide, effectuer le calcul du prix et mettre à jour la base de données
        if ($isFormValid) {
            $dateArrive = $form->get('DateArrive')->getData();
            $dateDepart = $form->get('DateDepart')->getData();
            $nights = $this->calculateNights($dateArrive, $dateDepart);
            $newPrix = $this->calculatePrix($property->getPrice(), $nights);

            // Mettre à jour le prix dans l'entité Property
            $property->setPrix($newPrix);

            // Enregistrement des modifications dans la base de données
            $this->entityManager->flush();
        }

        return $this->render('booking/booking.html.twig', [
            'property' => $property,
            'form' => $form->createView(),
            'isFormValid' => $isFormValid,
        ]);
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
