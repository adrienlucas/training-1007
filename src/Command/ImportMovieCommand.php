<?php

namespace App\Command;

use App\Gateway\OmdbGateway;
use App\Repository\MovieRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsCommand(
    name: 'app:import-movie',
    description: 'Import a movie from the OmdbAPI',
)]
class ImportMovieCommand extends Command
{

    public function __construct(
        private OmdbGateway $omdbGateway,
        private MovieRepository $movieRepository,
    )
    {
        parent::__construct();
    }


    protected function configure(): void
    {
        $this
            ->addArgument('title', InputArgument::REQUIRED, 'Title of the movie')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $title = $input->getArgument('title');

        try {
            $movie = $this->omdbGateway->getMovie($title);
        } catch(TransportExceptionInterface) {
            $io->error('Remote API error.');
            return Command::FAILURE;
        }

        if($movie === null) {
            $io->error(sprintf('Movie "%s" not found.', $title));
            return Command::INVALID;
        }

        $this->movieRepository->save($movie, true);

        $io->success(sprintf(
            'Movie "%s" imported successfuly.',
            $title,
        ));

        return Command::SUCCESS;
    }
}
