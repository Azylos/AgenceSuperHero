<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $messages = [
            ['id' => 1, 'content' => 'Transmission entrante de Coruscant...'],
            ['id' => 2, 'content' => 'Alerte: Anomalie détectée dans le secteur 7'],
            ['id' => 3, 'content' => 'Mise à jour du système en cours...'],
        ];

        return $this->render('home/index.html.twig', [
            'messages' => $messages
        ]);
    }

    #[Route('/api/messages', name: 'api_messages')]
    public function loadMessages(Request $request): JsonResponse
    {
        $page = $request->query->get('page', 1);
        
        // Simuler des messages supplémentaires
        $messages = [
            ['id' => $page * 3 + 1, 'content' => 'Nouveau rapport de Night City...'],
            ['id' => $page * 3 + 2, 'content' => 'Mise à jour des implants disponible'],
            ['id' => $page * 3 + 3, 'content' => 'Scanner: menace détectée à proximité'],
        ];

        return new JsonResponse([
            'messages' => $messages,
            'hasMore' => $page < 3
        ]);
    }
}
