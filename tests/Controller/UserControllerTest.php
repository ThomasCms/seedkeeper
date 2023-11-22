<?php

namespace App\Test\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $repository;
    private string $path = '/user/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(User::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

//    public function testShow(): void
//    {
//        $this->markTestIncomplete();
//        $fixture = new User();
//        $fixture->setEmail('My Title');
//        $fixture->setRoles('My Title');
//        $fixture->setPassword('My Title');
//
//        $this->manager->persist($fixture);
//        $this->manager->flush();
//
//        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
//
//        self::assertResponseStatusCodeSame(200);
//        self::assertPageTitleContains('User');
//
//        // Use assertions to check that the properties are properly displayed.
//    }
//
//    public function testEdit(): void
//    {
//        $this->markTestIncomplete();
//        $fixture = new User();
//        $fixture->setEmail('My Title');
//        $fixture->setRoles('My Title');
//        $fixture->setPassword('My Title');
//
//        $this->manager->persist($fixture);
//        $this->manager->flush();
//
//        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));
//
//        $this->client->submitForm('Update', [
//            'user[email]' => 'Something New',
//            'user[roles]' => 'Something New',
//            'user[password]' => 'Something New',
//        ]);
//
//        self::assertResponseRedirects('/user/');
//
//        $fixture = $this->repository->findAll();
//
//        self::assertSame('Something New', $fixture[0]->getEmail());
//        self::assertSame('Something New', $fixture[0]->getRoles());
//        self::assertSame('Something New', $fixture[0]->getPassword());
//    }
//
//    public function testRemove(): void
//    {
//        $this->markTestIncomplete();
//
//        $originalNumObjectsInRepository = count($this->repository->findAll());
//
//        $fixture = new User();
//        $fixture->setEmail('My Title');
//        $fixture->setRoles('My Title');
//        $fixture->setPassword('My Title');
//
//        $this->manager->persist($fixture);
//        $this->manager->flush();
//
//        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
//
//        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
//        $this->client->submitForm('Delete');
//
//        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
//        self::assertResponseRedirects('/user/');
//    }
}
