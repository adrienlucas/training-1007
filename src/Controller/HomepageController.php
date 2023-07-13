<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage', defaults: ['name' => 'World'])]
    #[Route('/hello/{name}', name: 'app_hello', requirements: ['name' => '\w+'])]
    public function index(Request $request, string $name): Response
    {
//        $name = $request->attributes->get('name', 'World');
        return $this->render('homepage/index.html.twig', [
            'controller_name' => $name,
        ]);
    }
}

// make:command
// Edit the generated command to add a required argument which is the title of the movie
//    expected usage : symfony console app:import-movie "The Matrix"
// Inject the OmdbGateway in the command
// Implement a `getMovie(string $title): array` method on the OmdbGateway
//      alternative: `getMovie(string $title): Movie`
//     (beware of the dolls)
// Use this method inside the command
// Create a new Movie with the retrieved data
// Inject and use the MovieRepository to persist the newly created movie