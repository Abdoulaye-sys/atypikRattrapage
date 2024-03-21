<?php
// src/Service/RecaptchaService.php

namespace App\Service;

use Google\Cloud\RecaptchaEnterprise\V1\RecaptchaEnterpriseServiceClient;
use Google\Cloud\RecaptchaEnterprise\V1\Event;
use Google\Cloud\RecaptchaEnterprise\V1\Assessment;
use Google\Cloud\RecaptchaEnterprise\V1\TokenProperties\InvalidReason;

class RecaptchaService
{
    public function createAssessment(string $recaptchaKey, string $token, string $project, string $action): void
    {
        // Créez le client reCAPTCHA.
        $client = new RecaptchaEnterpriseServiceClient();
        $projectName = $client->projectName($project);

        // Définissez les propriétés de l'événement à suivre.
        $event = (new Event())
            ->setSiteKey($recaptchaKey)
            ->setToken($token);

        // Créez la demande d'évaluation.
        $assessment = (new Assessment())
            ->setEvent($event);

        try {
            $response = $client->createAssessment(
                $projectName,
                $assessment
            );

            // Vérifiez si le jeton est valide.
            if ($response->getTokenProperties()->getValid() == false) {
                throw new \Exception('Invalid token');
            }

            // Vérifiez si l'action attendue a été exécutée.
            if ($response->getTokenProperties()->getAction() != $action) {
                throw new \Exception('Mismatched action');
            }

            // Obtenez le score de risques et le ou les motifs.
            printf('The score for the protection action is: %s', $response->getRiskAnalysis()->getScore());
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
