<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'register', methods: ['GET', 'POST'])]
    public function register(Request $request, EntityManagerInterface $entityManager, UserManager $userManager): Response
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('home');
        }

        $user = new User();
        $googleAuthenticatorSecretIsSet = $user->getGoogleAuthenticatorSecret() !== null;
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $qrCodeNeeded = $userManager->setSensitiveUserInfosFromForm($form, $user, $googleAuthenticatorSecretIsSet);

            $entityManager->persist($user);
            $entityManager->flush();

            if ($qrCodeNeeded) {
                return $this->redirectToRoute('qr_code_ga', [], Response::HTTP_SEE_OTHER);
            }

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
