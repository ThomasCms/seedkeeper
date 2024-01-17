<?php

namespace App\Controller;

use App\Service\QrCodeManager;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class QrCodeController extends AbstractController
{
    #[Route('/members/qr/ga', name: 'qr_code_ga')]
    public function displayGoogleAuthenticatorQrCode(GoogleAuthenticatorInterface $googleAuthenticator, QrCodeManager $qrCodeManager): Response
    {
        $user = $this->getUser();
        if (!($user instanceof TwoFactorInterface)) {
            throw new NotFoundHttpException('Cannot display QR code');
        }

        return new Response($qrCodeManager->displayQrCode($googleAuthenticator->getQRContent($user)), 200, ['Content-Type' => 'image/png']);
    }
}
