<?php

namespace App\Command;

use DateTime;
use Defuse\Crypto\Key;
use DirectoryIterator;
use RegexIterator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

class UpdateDefuseSecretCommand extends Command
{
    private KernelInterface $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('defuse:update-secret')
            ->setDescription('Update defuse library cryptographic secret. Running this command will prevent
            you from decrypting previously encrypted seeds')
        ;
    }

    /**
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Running this command will prevent you from decrypting previously encrypted seeds. Do you want to continue ? (Y/N)', false);

        if (!$helper->ask($input, $output, $question)) {
            return Command::SUCCESS;
        }

        $filesystem = new Filesystem();
        $now = new \DateTime();
        $secretKeyFile = $this->kernel->getProjectDir() . '/bin/secret-key';

        $filesystem->copy(
            $secretKeyFile,
            $secretKeyFile . '_' . $now->format('Y-m-d H:i:s') . '.old',
            true
        );

        $key = Key::createNewRandomKey()->saveToAsciiSafeString();

        file_put_contents($secretKeyFile , $key, LOCK_EX);

        $iterator = new RegexIterator(new DirectoryIterator($this->kernel->getProjectDir() . '/bin'), "/\\.old\$/i");

        // delete all secret-key_* files older than 1 year
        /** @var DirectoryIterator $file */
        foreach ($iterator as $file) {
            $dateTime = new DateTime(mb_str_split(explode('_', $file->getFilename())[1], 19)[0]);

            if ($now->diff($dateTime)->y >= 1) {
                $filesystem->remove($file->getPathname());
            }
        }

        return Command::SUCCESS;
    }
}
