<?php

namespace App\Controller;

use App\Repository\MissionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(MissionRepository $missionRepository): Response
    {
        $missions = $missionRepository->findBy(['status' => 'EN COURS']);
        
// Filtrer les missions dont les coordonnées sont non nulles
$missionsData = array_map(function($mission) {
    $latitude = $mission->getLatitude();
    $longitude = $mission->getLongitude();
    
    // Si l'une des coordonnées est nulle, on ne retourne rien
    if ($latitude === null || $longitude === null) {
        return null; 
    }

    return [
        'latitude' => $latitude,
        'longitude' => $longitude,
        'title' => $mission->getTitle(),
    ];
}, $missions);

// Enlever les éléments null du tableau
$missionsData = array_filter($missionsData);

// Re-indexer le tableau pour éviter des indices associatifs ou non numériques
$missionsData = array_values($missionsData);

// Vous devriez maintenant avoir un tableau avec des indices numériques séquentiels (0, 1, 2,...)
return $this->render('home/index.html.twig', [
    'missionsData' => json_encode($missionsData) // Convertir en JSON pour être utilisé dans JS
]);

    }      

}
