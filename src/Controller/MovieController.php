<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Gateway\OmdbGateway;
use App\Gateway\RemoteMovieResolver;
use App\Repository\MovieRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MovieController extends AbstractController
{
    #[Route('/movies', name: 'app_movie_list')]
    public function list(MovieRepository $movieRepository): Response
    {
        $movies = $movieRepository->findAll();

        return $this->render('movie/list.html.twig', [
            'movies' => $movies,
        ]);
    }

    #[Route('/movies/detail/{id}', name: 'app_movie_detail')]
    public function detail(Movie $movie, OmdbGateway $omdbGateway): Response
    {
        $moviePoster = $omdbGateway->getPoster($movie);

        return $this->render('movie/detail.html.twig', [
            'movie' => $movie,
            'moviePoster' => $moviePoster,
        ]);
    }

    #[Route('/movies/preview/{title}', name: 'app_movie_preview')]
    public function preview(
        string $title,
        OmdbGateway $omdbGateway): Response
    {
        $movie = $omdbGateway->getMovie($title);
        if ($movie === null) {
            throw $this->createNotFoundException('Movie not found on the remote API.');
        }

        $moviePoster = $omdbGateway->getPoster($movie);

        return $this->render('movie/detail.html.twig', [
            'movie' => $movie,
            'moviePoster' => $moviePoster,
        ]);
    }

    #[Route('/movies/create', name: 'app_movie_create')]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request, MovieRepository $movieRepository)
    {
        $form = $this->createForm(MovieType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Movie $movie */
            $movie = $form->getData();

            $currentUser = $this->getUser();
            $movie->setCreatedBy($currentUser);

            $movieRepository->save($movie, true);

            $this->addFlash('success', 'Your movie has been created successfully.');

            return $this->redirectToRoute('app_movie_detail', [
                'id' => $movie->getId()
            ]);
        }

        return $this->render('movie/create.html.twig', [
            'createMovieForm' => $form,
        ]);
    }

    #[Route('/movies/delete/{id}', name:'app_movie_delete')]
    #[IsGranted('delete', 'movie')]
    public function delete(Movie $movie, MovieRepository $movieRepository): Response
    {
        $movieRepository->remove($movie, true);

        $this->addFlash('success', sprintf('Movie "%s" was removed.', $movie->getTitle()));

        return $this->redirectToRoute('app_movie_list');
    }

    #[Route('/json-demo')]
    public function jsonDemo(): JsonResponse
    {
        return new JsonResponse(['message' => 'Hello world!']);
    }
}

