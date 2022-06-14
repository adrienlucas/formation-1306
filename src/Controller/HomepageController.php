<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     * @Route("/hello/{name}", name="app_hello")
     */
    public function homepage(string $name = 'world'): Response
    {
        $movieRepository = $this->getDoctrine()->getRepository(Movie::class);
        $latestMovie = $movieRepository->findLatest();

        return $this->render('homepage/index.html.twig', [
            'controller_name' => $name,
            'latest_movie' => $latestMovie
        ]);
    }
}
