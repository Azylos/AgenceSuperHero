<?php

namespace App\Controller;

use App\Entity\SuperHero;
use App\Form\SuperHeroType;
use App\Repository\PowerRepository;
use App\Service\FileUploader;
use App\Repository\SuperHeroRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/super-hero')]
final class SuperHeroController extends AbstractController
{
    #[Route(name: 'app_super_hero_index', methods: ['GET'])]
    public function index(Request $request, SuperHeroRepository $superHeroRepository, PowerRepository $powerRepository, PaginatorInterface $paginator): Response
    {
        $filterSelected = $request->query->get('sort', 's.name');
        $orderBySelected = $request->query->get('direction', 'asc');
        $powers = $powerRepository->findAll();
        $selectedPowerId = $request->query->get('power');
        
        if($selectedPowerId){
            $filter = $superHeroRepository->indexHeroFilter($filterSelected, $orderBySelected, $selectedPowerId);
        } else {
            $filter = $superHeroRepository->indexHeroFilter($filterSelected, $orderBySelected);
        }
    
        $pagination = $paginator->paginate(
            $filter,
            $request->query->getInt('page', 1),
            5,
            [
                'filter' => 's.name',
                'orderBy' => 'asc',
            ]
        );

        return $this->render('super_hero/index.html.twig', [
            'pagination' => $pagination,
            'powers' => $powers,
            'selectedPowerId' => $selectedPowerId,
        ]);
    }
    

    #[Route('/new', name: 'app_super_hero_new', methods: ['GET', 'POST'])]
    public function newHero(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $superHero = new SuperHero();
        $form = $this->createForm(SuperHeroType::class, $superHero);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imgHero = $form->get('imageName')->getData();

            if ($imgHero) {
                //envoie de l'img dans le service upload
                $imgHeroName = $fileUploader->upload($imgHero);
                $superHero->setImageName($imgHeroName);
            }

            $entityManager->persist($superHero);
            $entityManager->flush();

            return $this->redirectToRoute('app_super_hero_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('super_hero/new.html.twig', [
            'super_hero' => $superHero,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_super_hero_show', methods: ['GET'])]
    public function show(SuperHero $superHero): Response
    {
        return $this->render('super_hero/show.html.twig', [
            'super_hero' => $superHero,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_super_hero_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SuperHero $superHero, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SuperHeroType::class, $superHero);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_super_hero_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('super_hero/edit.html.twig', [
            'super_hero' => $superHero,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_super_hero_delete', methods: ['POST'])]
    public function delete(Request $request, SuperHero $superHero, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$superHero->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($superHero);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_super_hero_index', [], Response::HTTP_SEE_OTHER);
    }
}
