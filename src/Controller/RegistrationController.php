<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\Authenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;


class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, Authenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérification du reCAPTCHA
            $recaptcha = $request->request->get('g-recaptcha-response');
            $client = new \GuzzleHttp\Client();
            $response = $client->post('https://www.google.com/recaptcha/enterprise.js?render=6LeeTaApAAAAACfApD8al33jy2o1U7eLzeOOq6q8', [
                'form_params' => array(
                    'secret' => '6LeeTaApAAAAACfApD8al33jy2o1U7eLzeOOq6q8',
                    'response' => $recaptcha
                )
            ]);
            $result = json_decode($response->getBody()->getContents());

            if (!$result->success) {
                // Le reCAPTCHA n'est pas valide
                // Gérer l'erreur ici (par exemple, rediriger l'utilisateur avec un message d'erreur)
                return $this->redirectToRoute('app_register', ['error' => 'Le reCAPTCHA n\'a pas été validé. Veuillez réessayer.']);
            }

            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}