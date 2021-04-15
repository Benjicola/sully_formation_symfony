<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Command\Movie;

use App\Consumer\MovieInformationProvider;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * A command class that imports one movie from OMDb api and persist it.
 * To use this command, open a terminal and execute the following:
 *
 *     $ symfony console movie:import
 *
 * See https://symfony.com/doc/current/cookbook/console/console_command.html
 *
 * @author  Gaëtan Rolé-Dubruille <gaetan.role-dubruille@sensiolabs.com>
 */
class ImportOneOmdbMovieCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'movie:import';

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @var MovieInformationProvider
     */
    private $movieInformationProvider;

    public function __construct(
        EntityManagerInterface $entityManager,
        MovieInformationProvider $movieInformationProvider,
        string $name = null
    ) {
        $this->entityManager = $entityManager;

        parent::__construct($name);
        $this->movieInformationProvider = $movieInformationProvider;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Import and persist one OMDb movie by its IMDb id or title.')
            ->setHelp(<<<'DOC'
                            The <info>%command.name%</info> command import one OMDb movie:
                            <info>php %command.full_name%</info>
                            You can fetch a movie with <comment>--id or --title</comment> options:
                            <info>php %command.full_name%</info> <comment>--title=Avengers</comment>
                            Only one option is required, not <both class=""></both>
                            DOC
            )
            ->addOption('id', 'i', InputOption::VALUE_OPTIONAL, 'Fetch a movie by its IMDb Id.')
            ->addOption('title', 't', InputOption::VALUE_OPTIONAL, 'Fetch a movie by its title.')
        ;
    }

    /**
     * This method is executed after initialize(). It contains the logic to execute.
     *
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('OMDb Api import :');

        try {
            if ($this->isValidUserInputOptions($input->getOptions())) {
                $io->error('One only option: --id or --title is required.');

                return 1;
            }

            $movie = $input->getOption('id') ?
                $this->movieInformationProvider->findFromApi($input->getOption('id')) :
                $this->movieInformationProvider->findOneByTitleFromApi($input->getOption('title'));

            $this->entityManager->persist($movie);
            $this->entityManager->flush();

            $this->entityManager->refresh($movie);
        } catch (\Throwable $exception) {
            $io->error($exception->getMessage());

            return 1;
        }

        // A new SQL request to double check if a movie is well stored in Movie table.
        $io->success($movie.' movie is well imported. ID: '.$movie->getId());

        return 0;
    }

    private function isValidUserInputOptions(array $options): bool
    {
        return ($options['id'] && $options['title']) || (!$options['id'] && !$options['title']);
    }
}
