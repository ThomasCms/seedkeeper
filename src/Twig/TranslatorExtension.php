<?php

namespace App\Twig;

use App\Service\Translator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TranslatorExtension extends AbstractExtension
{
    private Translator $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('trans', [$this, 'trans']),
        ];
    }

    public function trans(string $id, array $parameters = [], string $domain = null, string $locale = null): string
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }
}
