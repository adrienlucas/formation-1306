<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Movie;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        ]);
    }

    /**
     * @Route("/load-movies", name="app_movie_load")
     */
    public function loadMovies(ManagerRegistry $doctrine): Response
    {
        $movieRepository = $doctrine->getRepository(Movie::class);
        $movieRepository->removeAll();

        $genreRepository = $doctrine->getRepository(Genre::class);
        $genreRepository->removeAll();

        $movie = new Movie();
        $movie->setTitle('The Matrix');
        $movie->setDescription('Neo takes the red pill.');

        $movieRepository->add($movie, true);

        $genre = new Genre();
        $genre->setName('Action');
        $genre->addMovie($movie);

        $genreRepository->add($genre, true);

        $movie = new Movie();
        $movie->setTitle('Avenger');

        $movieRepository->add($movie, true);

        return new JsonResponse('Movies loaded.');
    }
}
