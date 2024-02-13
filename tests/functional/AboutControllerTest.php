<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AboutControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();

        // Simuler une requête GET vers la page /about
        $client->request('GET', '/about');

        // Vérifier que la réponse est réussie
        $this->assertTrue($client->getResponse()->isSuccessful());

        // Vérifier que le contenu de la réponse contient le texte attendu
        $this->assertStringContainsString('A propos de notre histoire', $client->getResponse()->getContent());
    }
}
