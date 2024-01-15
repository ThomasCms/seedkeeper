<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionManager
{
    public function setLocaleInSession(SessionInterface $session, string $locale): void
    {
        if (!$session->isStarted()) {
            $session->start();
        }

        $session->set('_locale', $locale);
    }
}
