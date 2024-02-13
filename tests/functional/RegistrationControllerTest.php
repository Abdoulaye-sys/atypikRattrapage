<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegister(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        // Assurez-vous que la page de formulaire de registration est accessible
        $this->assertResponseIsSuccessful();

        // Remplacez le sélecteur du bouton en fonction de votre formulaire
        $form = $crawler->filter('form[name=registration_form]')->form();

        // Générez une adresse e-mail aléatoire
        $randomEmail = 'user' . uniqid() . '@example.com';

        // Simuler une soumission du formulaire avec des données fictives
        $client->submitForm('Inscription', [
            'registration_form[name]' => 'Abdoulaye',
            'registration_form[email]' => $randomEmail,
            'registration_form[phone]' => '0621349582',
            'registration_form[plainPassword]' => 'senegalA123*',
            'registration_form[agreeTerms]' => true,
        ]);

        // Vérifier que la redirection après une inscription réussie est correcte
        $this->assertResponseRedirects('/'); // Mettez l'URL de redirection attendue après l'inscription réussie

        // Vérifier que l'utilisateur est connecté après l'inscription
        $client->followRedirect();
        $this->assertSelectorTextContains('h1', 'Laissez-nous vous guider Accueil'); // Mettez le texte attendu sur la page après la connexion
    }
}
