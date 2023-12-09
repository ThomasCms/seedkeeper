<?php

namespace App\DataFixtures;

use App\Entity\Seed;
use App\Repository\UserRepository;
use App\Security\Cryptography\EncryptDecryptManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeedFixtures extends Fixture implements DependentFixtureInterface
{
    private UserRepository $userRepository;
    private EncryptDecryptManager $encryptDecryptManager;

    public function __construct(UserRepository $userRepository, EncryptDecryptManager $encryptDecryptManager)
    {
        $this->userRepository = $userRepository;
        $this->encryptDecryptManager = $encryptDecryptManager;
    }

    public function load(ObjectManager $manager): void
    {
        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            $seed = new Seed();
            $seed->setText($this->encryptDecryptManager->encryptString('one two three four five six seven eight nine ten eleven twelve'));
            $seed->setOwner($user);

            $manager->persist($seed);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
