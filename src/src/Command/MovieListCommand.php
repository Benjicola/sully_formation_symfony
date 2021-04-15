<?php

namespace App\Command;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Service\OMDbService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class ImportMovieCommand
 * @package App\Command
 */
class MovieListCommand extends Command
{
    /** @var MovieRepository $movieRepository */
    private MovieRepository $movieRepository;

    /**
     * MovieListCommand constructor.
     * @param string|null $name
     * @param OMDbService $omDbService
     */
    public function __construct(MovieRepository $movieRepository, string $name = null)
    {
        parent::__construct($name);
        $this->movieRepository = $movieRepository;
    }

    protected function configure()
    {
        $this
            ->setName('movie:list')
            ->setDescription('Liste les films en BdD')
            ->setHelp('Liste les films en BdD');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var FormatterHelper $formatter */
        $formatter = $this->getHelper('formatter');
        /** @var SymfonyStyle $io */
        $io = new SymfonyStyle($input, $output);
        /** @var Movie[] $movies */
        $movies = $this->movieRepository->findAll();

        $io->section('Liste des films en BdD');

        $moviesAsArray = [];

          /** Ou avec le array_map */
//        $moviesAsArray = array_map(
//            static function (Movie $movie) use($formatter) {
//                return [
//                    $movie->getId(),
//                    $movie->getTitle(),
//                    $movie->getReleased()->format('d/m/Y'),
//                    $formatter->truncate($movie->getDescription(), 80),
//                ];
//            },
//            $movies = $this->movieRepository->findAll()
//        );

        /** @var Movie $current */
        foreach ($movies as $current) {
            $moviesAsArray[] = [
                $current->getId(),
                $current->getTitle(),
                $current->getReleased()->format('d/m/Y'),
                $formatter->truncate($current->getDescription(), 80),
            ];
        }

        $io->table(
            [
                'IDA',
                'Titre',
                'Année de parution',
                'Description',
            ],
            $moviesAsArray
        );

        return Command::SUCCESS;
    }
}