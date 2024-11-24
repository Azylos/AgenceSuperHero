<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamType;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/team')]
final class TeamController extends AbstractController
{
    #[Route(name: 'app_team_index', methods: ['GET'])]
    public function index(TeamRepository $teamRepository): Response
    {
        return $this->render('team/index.html.twig', [
            'teams' => $teamRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_team_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $team = new Team();
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($team->getMembers() as $member) {
                if ($member->isAvailable()) {
                    $member->setAvailable(false);
                    $entityManager->persist($member);
                }
            }
    
            $entityManager->persist($team);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_team_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('team/new.html.twig', [
            'team' => $team,
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/{id}', name: 'app_team_show', methods: ['GET'])]
    public function show(Team $team): Response
    {
        return $this->render('team/show.html.twig', [
            'team' => $team,
        ]);
    }
    
    #[Route('/{id}/edit', name: 'app_team_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Team $team, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les membres originaux avant toute modification
        $members = new ArrayCollection();
        foreach ($team->getMembers() as $member) {
            $members->add($member);
        }
    
        // Créer le formulaire et passer l'objet `team`
        $form = $this->createForm(TeamType::class, $team, [
            'team' => $team,
        ]);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Membres retirés : rendre disponibles
            foreach ($members as $member) {
                $found = false;
                foreach ($team->getMembers() as $currentMember) {
                    if ($currentMember->getId() === $member->getId()) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $member->setAvailable(true);
                    $entityManager->persist($member);
                }
            }
    
            // Membres ajoutés : rendre indisponibles
            foreach ($team->getMembers() as $currentMember) {
                $found = false;
                foreach ($members as $member) {
                    if ($currentMember->getId() === $member->getId()) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $currentMember->setAvailable(false);
                    $entityManager->persist($currentMember);
                }
            }
    
            $entityManager->flush();
    
            return $this->redirectToRoute('app_team_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('team/edit.html.twig', [
            'team' => $team,
            'form' => $form->createView(),
            'members' => $team->getMembers(),
        ]);
    }
     

    #[Route('/{id}', name: 'app_team_delete', methods: ['POST'])]
    public function delete(Request $request, Team $team, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$team->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($team);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_team_index', [], Response::HTTP_SEE_OTHER);
    }
}
