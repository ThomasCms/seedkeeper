<?php

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class UpdateDefuseSecretCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

//        $command = $application->find('defuse:update-secret');
//        $commandTester = new CommandTester($command);
//        $commandTester->execute([
//            // pass arguments to the helper
//            'username' => 'Wouter',
//
//            // prefix the key with two dashes when passing options,
//            // e.g: '--some-option' => 'option_value',
//            // use brackets for testing array value,
//            // e.g: '--some-option' => ['option_value'],
//        ]);
//        $commandTester->setInputs(['y']);
//        $commandTester->setInputs(['n']);
//
//        $commandTester->assertCommandIsSuccessful();
//
//        // the output of the command in the console
//        $output = $commandTester->getDisplay();
//        $this->assertStringContainsString('Username: Wouter', $output);
    }
}
