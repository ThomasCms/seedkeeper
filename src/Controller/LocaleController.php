<?php

namespace App\Controller;

use App\Service\LocaleSessionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LocaleController extends AbstractController
{
    #[Route('/locale/{_locale<%app.supported_locales%>}', name: 'set_locale', options: ['expose' => true], methods: ['POST'])]
    public function setLocale(Request $request, LocaleSessionManager $localeSessionManager): Response
    {
        $localeSessionManager->setLocaleInSession($request->getSession(), $request->getLocale());

        return new Response();
    }
}
