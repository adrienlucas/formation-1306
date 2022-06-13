<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    /**
     * @Route("/movie", name="app_movie_show")
     */
    public function show(): Response
    {
        $movie = [
            'title' => 'The Matrix',
            'description' => 'Neo takes the red pill'
        ];

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
            'controller_name' => 'MovieController',
        ]);
    }
}
