<?php

namespace App\Service;

use App\Entity\User;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManager
{
    private UserPasswordHasherInterface $userPasswordHasher;
    private GoogleAuthenticator $authenticator;

    public function __construct(
        UserPasswordHasherInterface $userPasswordHasher,
        GoogleAuthenticator $authenticator
    ) {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->authenticator = $authenticator;
    }

    public function setSensitiveUserInfosFromForm(FormInterface $form, User &$user, bool $gASecretWasSet): bool
    {
        $qrCodeNeeded = false;
        $plainPassword = $form->get('plainPassword')->getData();

        if (!empty($plainPassword)) {
            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    $plainPassword
                )
            );
        }

        $activate2fa = $form->get('googleAuthenticatorSecret')->getData();

        if ($gASecretWasSet && !$activate2fa) {
            $user->setGoogleAuthenticatorSecret(null);
        }

        if (!$gASecretWasSet && $activate2fa) {
            $user->setGoogleAuthenticatorSecret($this->authenticator->generateSecret());
            $qrCodeNeeded = true;
        }

        return $qrCodeNeeded;
    }
}
