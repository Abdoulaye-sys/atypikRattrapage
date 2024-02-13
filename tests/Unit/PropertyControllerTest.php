<?php
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PropertyControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/property');
    
        // Affiche le contenu de la réponse en cas d'échec
        if (!$client->getResponse()->isSuccessful()) {
            echo $client->getResponse()->getContent();
        }
    
        $this->assertResponseIsSuccessful();

        // Utilise un sélecteur plus spécifique pour trouver l'élément h1
        $this->assertSelectorTextContains('h2.text-secondary', 'Nos hébergements');
    }
}
