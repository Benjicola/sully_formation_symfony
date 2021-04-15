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

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * A Movie command class that lists all existing movies.
 * To use this command, open a terminal and execute the following:
 *
 *     $ symfony console movie:list
 *
 * See https://symfony.com/doc/current/cookbook/console/console_command.html
 *
 * @author  Gaëtan Rolé-Dubruille <gaetan.role-dubruille@sensiolabs.com>
 */
class ListAllMoviesCommand extends Command
{
    /** @var int */
    private const MAX_MOVIE_RESULT = 100;

    /** @var string */
    protected static $defaultName = 'movie:list';

    /** @var MovieRepository */
    private $movieRepository;

    public function __construct(MovieRepository $movieRepository)
    {
        parent::__construct();

        $this->movieRepository = $movieRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Lists all existing movies.')
            ->setHelp(<<<'DOC'
                        The <info>%command.name%</info> command lists all existing movies:
                        <info>php %command.full_name%</info>
                        You can use an option to limit the result:
                        <info>php %command.full_name%</info> <comment>--max-results=50</comment>
                        This options is set to 100 by default.
                        DOC
            )
            ->addOption(
                'max-results',
                null,
                InputOption::VALUE_OPTIONAL,
                'Limits the number of listed movies.',
                self::MAX_MOVIE_RESULT
            )
        ;
    }

    /**
     * This method is executed after initialize(). It contains the logic to execute.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $moviesAsPlainArrays = array_map(
            static function (Movie $movie) {
                return [$movie->getUuid(), $movie->getTitle(), $movie->getGenreNames()];
            },
            $this->movieRepository->findBy([], ['id' => 'DESC'], $input->getOption('max-results'))
        );

        $io = new SymfonyStyle($input, $output);

        $io->title('Current Movies present in DB :');
        $io->table(['UUID', 'Title', 'Genres'], $moviesAsPlainArrays);

        return self::SUCCESS;
    }
}
