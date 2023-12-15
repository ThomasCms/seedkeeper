<?php

namespace App\Controller;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/my-account')]
class UserController extends AbstractController
{
    #[Route('/', name: 'profile', methods: ['GET'])]
    public function show(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/edit', name: 'edit_user', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();

            if (!empty($plainPassword)) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $plainPassword
                    )
                );
            }

            $entityManager->flush();

            return $this->redirectToRoute('profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/delete', name: 'delete_user', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_csrf_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();

            $tokenStorage->setToken(null);
            $request->getSession()->clear();
        }

        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    }
}
