<?php

namespace App\Controller;

use App\Entity\Mealplan;
use App\Form\MealplanType;
use App\Repository\MealplanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/mealplan')]
final class MealplanController extends AbstractController
{
    public function __construct(private SluggerInterface $slugger)
    {
    }

    #[Route(name: 'app_mealplan_index', methods: ['GET'])]
    public function index(MealplanRepository $mealplanRepository): Response
    {
        return $this->render('mealplan/index.html.twig', [
            'mealplans' => $mealplanRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_mealplan_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $mealplan = new Mealplan();
        $form = $this->createForm(MealplanType::class, $mealplan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $photofile */
            $photofile = $form->get('photo')->getData();
            if ($photofile) {
                $filename = pathinfo($photofile->getClientOriginalName(), PATHINFO_FILENAME);
                $originalname = $this->slugger->slug($filename);
                $newfilename = $originalname . '-' . uniqid() . '.' . $photofile->guessExtension();
                try {
                    $photofile->move(
                        $this->getParameter('photos_directory'),
                        $newfilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $mealplan->setPhotoFilename($newfilename);
            }

            $entityManager->persist($mealplan);
            $entityManager->flush();

            return $this->redirectToRoute('app_mealplan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('mealplan/new.html.twig', [
            'mealplan' => $mealplan,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mealplan_show', methods: ['GET'])]
    public function show(Mealplan $mealplan): Response
    {
        return $this->render('mealplan/show.html.twig', [
            'mealplan' => $mealplan,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_mealplan_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mealplan $mealplan, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MealplanType::class, $mealplan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $photofile */
            $photofile = $form->get('photo')->getData();
            if ($photofile) {
                $filename = pathinfo($photofile->getClientOriginalName(), PATHINFO_FILENAME);
                $originalname = $this->slugger->slug($filename);
                $newfilename = $originalname . '-' . uniqid() . '.' . $photofile->guessExtension();
                try {
                    $photofile->move(
                        $this->getParameter('photos_directory'),
                        $newfilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $mealplan->setPhotoFilename($newfilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_mealplan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('mealplan/edit.html.twig', [
            'mealplan' => $mealplan,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mealplan_delete', methods: ['POST'])]
    public function delete(Request $request, Mealplan $mealplan, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mealplan->getId(), $request->request->get('_token'))) {
            $entityManager->remove($mealplan);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_mealplan_index', [], Response::HTTP_SEE_OTHER);
    }
}