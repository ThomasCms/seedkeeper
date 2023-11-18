<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class UserFixtures extends Fixture
{
    private const DEFAULT_PASSWORD = 'testtest';

    private $passwordHasherFactory;

    public function __construct(PasswordHasherFactoryInterface $encoderFactory)
    {
        $this->passwordHasherFactory = $encoderFactory;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('admin@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasherFactory->getPasswordHasher(User::class)->hash(self::DEFAULT_PASSWORD));

        $manager->persist($user);
        $manager->flush();
    }
}
