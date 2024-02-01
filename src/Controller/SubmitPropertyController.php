<?php
// src/Controller/SubmitPropertyController.php
namespace App\Controller;

use App\Entity\Property;
use App\Form\PropertyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubmitPropertyController extends AbstractController
{
    #[Route('/submit/property', name: 'app_submit_property')]
    public function submitProperty(Request $request, EntityManagerInterface $entityManager): Response
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérez le fichier téléchargé ici
            $uploadedFile = $form['pimage']->getData();

            if ($uploadedFile) {
                // Assurez-vous que le dossier d'upload existe
                $uploadDirectory = $this->getParameter('kernel.project_dir') . '/public/images';
                if (!file_exists($uploadDirectory)) {
                    mkdir($uploadDirectory, 0777, true);
                }

                // Générez un nom unique pour l'image
                $newFilename = uniqid() . '.' . $uploadedFile->guessExtension();

                // Déplacez le fichier téléchargé vers le dossier d'upload
                $uploadedFile->move($uploadDirectory, $newFilename);

                // Mettez à jour le champ pimage de votre entité
                $property->setPimage($newFilename);
            }

            // Persistez les données en base de données
            $entityManager->persist($property);
            $entityManager->flush();

            // Redirigez l'utilisateur après la soumission du formulaire
            return $this->redirectToRoute('app_property');
        }

        return $this->render('submit_property/submit_property.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}