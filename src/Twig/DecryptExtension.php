<?php

namespace App\Twig;

use App\Security\Cryptography\EncryptDecryptManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DecryptExtension extends AbstractExtension
{
    private EncryptDecryptManager $encryptDecryptManager;

    public function __construct(EncryptDecryptManager $encryptDecryptManager)
    {
        $this->encryptDecryptManager = $encryptDecryptManager;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('decrypt', [$this, 'decryptString']),
        ];
    }

    /**
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     * @throws \Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException
     */
    public function decryptString(string $string): string
    {
        return $this->encryptDecryptManager->decryptString($string);
    }
}
