<?php

namespace App\Controller;

use App\Entity\Seed;
use App\Form\SeedType;
use App\Repository\SeedRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_USER')]
#[Route('/seed')]
class SeedController extends AbstractController
{
    #[Route('/', name: 'seed_index', methods: ['GET'])]
    public function index(SeedRepository $seedRepository): Response
    {
        return $this->render('seed/index.html.twig', [
            'seeds' => $seedRepository->findBy(['owner' => $this->getUser()->getId()]),
        ]);
    }

    #[Route('/new', name: 'new_seed', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $seed = new Seed();
        $form = $this->createForm(SeedType::class, $seed);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $seed->setOwner($this->getUser());

            $entityManager->persist($seed);
            $entityManager->flush();

            return $this->redirectToRoute('seed_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('seed/new.html.twig', [
            'seed' => $seed,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show_seed', methods: ['GET'])]
    public function show(Seed $seed): Response
    {
        $this->denyAccessUnlessGranted('show', $seed);

        return $this->render('seed/show.html.twig', [
            'seed' => $seed,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit_seed', methods: ['GET', 'POST'])]
    public function edit(Request $request, Seed $seed, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('edit', $seed);

        $form = $this->createForm(SeedType::class, $seed);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('seed_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('seed/edit.html.twig', [
            'seed' => $seed,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete_seed', methods: ['POST'])]
    public function delete(Request $request, Seed $seed, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('delete', $seed);

        if ($this->isCsrfTokenValid('delete'.$seed->getId(), $request->request->get('_token'))) {
            $entityManager->remove($seed);
            $entityManager->flush();
        }

        return $this->redirectToRoute('seed_index', [], Response::HTTP_SEE_OTHER);
    }
}
