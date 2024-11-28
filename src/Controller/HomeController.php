<?php

namespace App\Controller;

use App\Repository\MissionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, MissionRepository $missionRepository, PaginatorInterface $paginator): Response
    {
        $status = $request->query->get('mStatus', 'EN COURS');;
        $missions = $missionRepository->findBy(['status' => 'EN COURS']);
        $missionSelected = $missionRepository->currentOrPendingMission($status);

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

        $pagination = $paginator->paginate(
            $missionSelected,
            $request->query->getInt('page', 1),
            2,
        );

        return $this->render('home/index.html.twig', [
            'missionsData' => json_encode($missionsData), // Convertir en JSON pour être utilisé dans JS
            'missions' => $pagination,
            'selectedStatus' => $status,
        ]);

    }      

}
