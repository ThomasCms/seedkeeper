<?php

namespace App\Security;

use App\Repository\UserRepository;
use App\Service\SessionManager;
use App\Service\Translator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractAuthenticator
{
    use TargetPathTrait;

    private UserRepository $userRepository;
    private SessionManager $sessionManager;
    private Translator $translator;
    private UrlGenerator $urlGenerator;

    public function __construct(
        UserRepository $userRepository,
        SessionManager $sessionManager,
        Translator $translator,
        UrlGenerator $urlGenerator
    ) {
        $this->userRepository = $userRepository;
        $this->sessionManager = $sessionManager;
        $this->translator = $translator;
        $this->urlGenerator = $urlGenerator;
    }

    public function supports(Request $request): bool
    {
        return ($request->getPathInfo() === '/login' && $request->isMethod('POST'));
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('_username');
        $password = $request->request->get('_password');
        $rememberMe = $request->request->get('_remember_me');
        $badges = [
            new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token'))
        ];

        if (!empty($rememberMe)) {
            $badges[] = new RememberMeBadge();
        }

        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            throw new UserNotFoundException();
        }

        $this->sessionManager->setLocaleInSession($request->getSession(), $user->getLocale());

        return new Passport(
            new UserBadge($email, function($userIdentifier) {
                // I have no control over when this function will be executed and I have no idea how to avoid duplicating this request atm
                // Feel free to give me suggestions
                $user = $this->userRepository->findOneBy(['email' => $userIdentifier]);

                if (!$user) {
                    throw new UserNotFoundException();
                }

                return $user;
            }),
            new PasswordCredentials($password),
            $badges
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // TODO: watch https://symfony.com/doc/6.4/security.html#login-programmatically && https://symfonycasts.com/screencast/symfony4-security/registration-auth
        // Next steps: display qrcode successfully after register. Then create and display recover codes

        return new RedirectResponse($this->urlGenerator->generate('home'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => $this->translator->trans('login.failed'),
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
