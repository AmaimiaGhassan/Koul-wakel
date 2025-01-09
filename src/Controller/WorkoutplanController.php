<?php

namespace App\Controller;

use App\Entity\Workoutplan;
use App\Form\WorkoutplanType;
use App\Repository\WorkoutplanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/workoutplan')]
final class WorkoutplanController extends AbstractController
{
    #[Route(name: 'app_workoutplan_index', methods: ['GET'])]
    public function index(WorkoutplanRepository $workoutplanRepository): Response
    {
        return $this->render('workoutplan/index.html.twig', [
            'workoutplans' => $workoutplanRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_workoutplan_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $workoutplan = new Workoutplan();
        $form = $this->createForm(WorkoutplanType::class, $workoutplan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($workoutplan);
            $entityManager->flush();

            return $this->redirectToRoute('app_workoutplan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('workoutplan/new.html.twig', [
            'workoutplan' => $workoutplan,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_workoutplan_show', methods: ['GET'])]
    public function show(Workoutplan $workoutplan): Response
    {
        return $this->render('workoutplan/show.html.twig', [
            'workoutplan' => $workoutplan,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_workoutplan_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Workoutplan $workoutplan, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WorkoutplanType::class, $workoutplan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_workoutplan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('workoutplan/edit.html.twig', [
            'workoutplan' => $workoutplan,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_workoutplan_delete', methods: ['POST'])]
    public function delete(Request $request, Workoutplan $workoutplan, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$workoutplan->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($workoutplan);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_workoutplan_index', [], Response::HTTP_SEE_OTHER);
    }
}
