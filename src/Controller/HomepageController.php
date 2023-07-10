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
