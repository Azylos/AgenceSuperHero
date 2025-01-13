<?php

namespace App\Controller;

use App\Entity\Mission;
use App\Form\MissionType;
use App\Service\GeocodingService;
use App\Repository\MissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/mission')]
final class MissionController extends AbstractController
{
    #[Route(name: 'app_mission_index', methods: ['GET'])]
    public function index(Request $request, MissionRepository $missionRepository, PaginatorInterface $paginator): Response
    {
        $statusSelected = $request->query->get('status');

        $filter = $missionRepository->missionFilterStatus($statusSelected);

        $pagination = $paginator->paginate(
            $filter,
            $request->query->getInt('page', 1),
            5,
        );

        return $this->render('mission/index.html.twig', [
            'pagination' => $pagination,
            'statusSelected' => $statusSelected
        ]);
    }

    #[Route('/new', name: 'app_mission_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, GeocodingService $geocodingService): Response
    {
        $mission = new Mission();
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer la localisation entrée par l'utilisateur
            $location = $mission->getLocation();

            // Effectuer le géocodage pour récupérer latitude/longitude
            $coordinates = $geocodingService->getCoordinatesByLocation($location);

            // Si des coordonnées sont trouvées, les attribuer à la mission
            if ($coordinates) {
                $mission->setLatitude($coordinates['latitude']);
                $mission->setLongitude($coordinates['longitude']);
            } else {
                $mission->setLatitude(null);
                $mission->setLongitude(null);
            }

            $entityManager->persist($mission);
            $entityManager->flush();

            return $this->redirectToRoute('app_mission_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('mission/new.html.twig', [
            'mission' => $mission,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mission_show', methods: ['GET'])]
    public function show(Mission $mission): Response
    {
        return $this->render('mission/show.html.twig', [
            'mission' => $mission,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_mission_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mission $mission, EntityManagerInterface $entityManager, GeocodingService $geocodingService): Response
    {
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $location = $mission->getLocation();

            $coordinates = $geocodingService->getCoordinatesByLocation($location);
    
            if ($coordinates) {
                $mission->setLatitude($coordinates['latitude']);
                $mission->setLongitude($coordinates['longitude']);
            } else {
                $this->addFlash('error', 'La localisation spécifiée n\'a pas pu être géocodée.');
                $mission->setLatitude(null);
                $mission->setLongitude(null);
            }
            
            $entityManager->flush();

            return $this->redirectToRoute('app_mission_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('mission/edit.html.twig', [
            'mission' => $mission,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mission_delete', methods: ['POST'])]
    public function delete(Request $request, Mission $mission, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mission->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($mission);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_mission_index', [], Response::HTTP_SEE_OTHER);
    }
}
