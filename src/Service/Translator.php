<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

class Translator
{
    private TranslatorInterface $translator;
    private RequestStack $requestStack;

    public function __construct(
        TranslatorInterface $translator,
        RequestStack $requestStack
    ) {
        $this->translator = $translator;
        $this->requestStack = $requestStack;
    }

    public function trans(string $id, array $parameters = [], string $domain = null, string $locale = null): string
    {
        if ($locale === null) {
            $locale = $this->requestStack->getSession()->get('_locale');
        }

        return $this->translator->trans($id, $parameters, $domain, $locale);
    }
}
