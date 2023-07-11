<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    #[Route('/movies', name: 'app_movie_list')]
    public function list(): Response
    {
        $movies = ['The Matrix', 'Indiana Jones'];

        return $this->render('movie/list.html.twig', [
            'movies' => $movies,
        ]);
    }

    #[Route('/movies/detail', name: 'app_movie_detail')]
    /**
     * @Route
     */
    public function detail(): Response
    {
        $movie = [
            'title' => 'Some movie',
            'plot' => 'A movie about a movie',
            'releaseDate' => new \DateTime('2020-01-01'),
        ];
        return $this->render('movie/detail.html.twig', [
            'movie' => $movie,
        ]);
    }
}


// make:entity Movie
//  title (string)
//  plot (text)
//  releaseDate (date)
// make:entity Genre
//  name (string)
//  movies (relation)

// make:migration & Go check what's inside the generated migration
// Execute the migration doctrine:migration:migrate

// doctrine:query:sql "select * from movie" (empty, but no errors)