<?php

namespace App\Security\Cryptography;

use App\Entity\Seed;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use Defuse\Crypto\Key;
use Symfony\Component\HttpKernel\KernelInterface;

class EncryptDecryptManager
{
    private Key $key;

    /**
     * EncryptDecryptManager constructor.
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    public function __construct(KernelInterface $kernel)
    {
        $keyAscii = file_get_contents($kernel->getProjectDir() . '/bin/secret-key');

        $this->key = Key::loadFromAsciiSafeString($keyAscii);
    }

    /**
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    public function encryptString(string $string): string
    {
        // Since the documentation says that string length cannot be hidden by cryptography, we're going to randomly increase the string length
        // https://github.com/defuse/php-encryption/blob/master/docs/FAQ.md
        $x = mt_rand(15, 150);

        for ($i = 0; $i < $x; ++$i) {
            $string = $string . ' ';
        }

        return Crypto::encrypt($string, $this->key);
    }

    /**
     * @throws WrongKeyOrModifiedCiphertextException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    public function decryptString(string $ciphertext): string
    {
        return Crypto::decrypt($ciphertext, $this->key);
    }

    /**
     * @throws WrongKeyOrModifiedCiphertextException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    public function decryptSeed(Seed $seed): Seed
    {
        $seed->setText(trim($this->decryptString($seed->getText())));

        return $seed;
    }
}
