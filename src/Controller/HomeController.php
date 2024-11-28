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
    
        // Filtrer les missions dont les coordonnées sont nulles
        $missionsData = array_filter(array_map(function($mission) {
            $latitude = $mission->getLatitude();
            $longitude = $mission->getLongitude();
            
            // Si l'une des coordonnées est nulle, ne pas inclure cette mission
            if ($latitude === null || $longitude === null) {
                return null; 
            }
    
            return [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'title' => $mission->getTitle(),
            ];
        }, $missions));
    
        $missionsData = array_filter($missionsData);

        return $this->render('home/index.html.twig', [
            'missionsData' => json_encode($missionsData) // Convertir en JSON pour être utilisé dans JS
        ]);
    }    

}
