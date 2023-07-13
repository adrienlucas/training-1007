<?php
declare(strict_types=1);

namespace App\Gateway;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Repository\GenreRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbGateway
{

    public function __construct(
        private HttpClientInterface $httpClient,
        private string $apiKey,
        private GenreRepository $genreRepository,
    ) {
    }

    public function getPoster(Movie $movie): string
    {
        $response = $this->httpClient->request('GET', sprintf(
            'https://www.omdbapi.com/?apikey=%s&t=%s',
            $this->apiKey,
            $movie->getTitle(),
        ));

        $jsonResponse = $response->toArray();
        if (array_key_exists('Error', $jsonResponse)) {
           return '';
        }

        return $response->toArray()['Poster'];
    }

    public function getMovie(string $title): ?Movie
    {
        $response = $this->httpClient->request('GET', sprintf(
            'https://www.omdbapi.com/?apikey=%s&t=%s',
            $this->apiKey,
            $title,
        ));

        $jsonResponse = $response->toArray();

        if (array_key_exists('Error', $jsonResponse)) {
            return null;
        }

        $title = $jsonResponse['Title'];
        $movie = new Movie();
        $movie->setTitle($title);
        $movie->setPlot($jsonResponse['Plot']);
        $movie->setReleasedAt(new \DateTime($jsonResponse['Released']));

        $genres = explode(',', $jsonResponse['Genre']);
        foreach($genres as $genre) {
            $genre = trim($genre);
            $persistedGenre = $this->genreRepository->findOneBy(['name' => $genre]);
            if($persistedGenre === null) {
                $persistedGenre = new Genre();
                $persistedGenre->setName($genre);
                $this->genreRepository->save($persistedGenre);
            }

            $movie->addGenre($persistedGenre);
        }

        return $movie;
    }
}