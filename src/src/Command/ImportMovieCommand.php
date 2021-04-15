<?php

namespace App\Command;

use App\Entity\Movie;
use App\Service\OMDbService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ImportMovieCommand
 * @package App\Command
 */
class ImportMovieCommand extends Command
{
    /** @var OMDbService $omDbService */
    private OMDbService $omDbService;

    /**
     * ImportMovieCommand constructor.
     * @param string|null $name
     * @param OMDbService $omDbService
     */
    public function __construct(OMDbService $omDbService, string $name = null)
    {
        parent::__construct($name);
        $this->omDbService = $omDbService;
    }

    protected function configure()
    {
        $this
            ->setName('omdb:import-movie')
            ->setDescription('Importe un film via son id ou son titre')
            ->setHelp('Importe un film via son id ou son titre')
            ->addOption('id', null, InputOption::VALUE_OPTIONAL, 'id du film')
            ->addOption('title', null,InputOption::VALUE_OPTIONAL, 'titre du film')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $progressBar = new ProgressBar($output, 60);
        $formatter = $this->getHelper('formatter');

        $id = $input->getOption('id');
        $title = $input->getOption('title');

        $progressBar->advance(15);
        $output->writeln('');

        try {
            if ($id) {
                $errorMessages = ['Recap', 'ID demandé : ' . $id];
                $formattedBlock = $formatter->formatBlock($errorMessages, 'info');
                $output->writeln($formattedBlock);
                $result = $this->omDbService->getMovieById($id);

                $progressBar->advance(15);
                $output->writeln('');
            } elseif ($title) {
                $messages = ['Recap', 'Titre demandé : ' . $title];
                $formattedBlock = $formatter->formatBlock($messages, 'info');
                $output->writeln($formattedBlock);

                $result = $this->omDbService->getMovieByTitle($title);

                $progressBar->advance(15);
                $output->writeln('');
            } else {
                $messages = ['Attention!', 'Fournir l id ou le titre'];
                $formattedBlock = $formatter->formatBlock($messages, 'error');
                $output->writeln($formattedBlock);

                $progressBar->finish();
                $output->writeln('');
                return Command::FAILURE;
            }
        }catch (\Exception $exc) {
            $errorMessages = ['Arg! Erreur', $exc->getMessage()];
            $formattedBlock = $formatter->formatBlock($errorMessages, 'error');
            $output->writeln($formattedBlock);
            $progressBar->finish();
            $output->writeln('');
            return Command::FAILURE;
        }

        $messages = [
            'Infos sur ce film',
            'Titre du film : '.$result['Title'],
            'Année : '.$result['Year'],
            'ID : '.$result['imdbID'],
            'Pays : '.$result['Country'],
            'Description : '.$result['Plot']
            ];
        $formattedBlock = $formatter->formatBlock($messages, 'comment');
        $output->writeln($formattedBlock);

//        dump($result);

        $progressBar->advance(15);
        $output->writeln('');
        $movie = $this->omDbService->createMovie($result);
        $progressBar->advance(15);
        $output->writeln('');
//        $this->omDbService->saveMovie($movie);

        $messages = ['Enregistrement en base okay', 'ID du film en base : '.$movie->getId()];
        $formattedBlock = $formatter->formatBlock($messages, 'info');
        $output->writeln($formattedBlock);

        $progressBar->finish();

        return Command::SUCCESS;
    }
}