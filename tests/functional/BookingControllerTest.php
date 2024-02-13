<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookingControllerTest extends WebTestCase
{
    public function testShowBookingPage(): void
    {
        $client = static::createClient();

        // Simuler une requête GET vers la page de réservation
        $client->request('GET', '/booking/1');

        // Ignorer la redirection et vérifier que la réponse est réussie
        $this->assertResponseIsSuccessfulIgnoreRedirect($client->getResponse());
    }

    private function assertResponseIsSuccessfulIgnoreRedirect($response): void
    {
        // Ignorer les redirections (302)
        if ($response->isRedirect()) {
            // Vérifier que l'URL cible de la redirection est '/login'
            $this->assertSame('/login', $response->getTargetUrl());
        } else {
            // Vérifier que la réponse est réussie
            $this->assertTrue($response->isSuccessful());
        }
    }
}
