<?php

namespace App\Command;

use App\Entity\Movie;
use App\Gateway\MovieNotFoundException;
use App\Gateway\OmdbGateway;
use App\Repository\MovieRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class MovieImportCommand extends Command
{
    protected static $defaultName = 'app:movie:import';
    protected static $defaultDescription = 'Import movie by its title.';

    private $omdbGateway;
    private $movieRepository;

    public function __construct(OmdbGateway $omdbGateway, MovieRepository $movieRepository)
    {
        $this->omdbGateway = $omdbGateway;
        $this->movieRepository = $movieRepository;

        parent::__construct();
    }


    protected function configure(): void
    {
        $this
            ->addArgument('title', InputArgument::OPTIONAL, 'The title of the movie to import.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $formatter = new SymfonyStyle($input, $output);

        $title = $input->getArgument('title');

        if($title === null) {
            $title = $formatter->ask('Which movie do you want to import ?');
        }

        $output->writeln(sprintf('Importing movie "%s"...', $title));

        try {
            $movie = $this->omdbGateway->getMovieByTitle($title);
        }catch(MovieNotFoundException $exception) {
            $title = $formatter->ask('Movie was not found, do you want to try another title ?');
            $movie = $this->omdbGateway->getMovieByTitle($title);
        }

        $formatter->table(
            ['Title', 'Description'],
            [[$movie->getTitle(), $movie->getDescription()]]
        );

        $doImport = $formatter->askQuestion(new ConfirmationQuestion('Are you sure you want to import this movie ?'));

        if($doImport) {
            $this->movieRepository->add($movie, true);
        }

        return Command::SUCCESS;
    }
}
