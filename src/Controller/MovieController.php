<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\User;
use App\Form\MovieType;
use App\Gateway\OmdbGateway;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    /**
     * @Route("/movies", name="app_movie_list")
     */
    public function list(ManagerRegistry $doctrine): Response
    {
        $movieRepository = $doctrine->getRepository(Movie::class);
        $movies = $movieRepository->findAll();

        return $this->render('movie/list.html.twig', [
            'movies' => $movies
        ]);
    }

    /**
     * @Route("/movie/{id}", name="app_movie_show", requirements={"id": "\d+"})
     */
    public function show(Movie $movie, OmdbGateway $omdbGateway): Response
    {
        $poster = $omdbGateway->getPosterByMovieTitle($movie->getTitle());

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
            'movie_poster' => $poster,
        ]);
    }


    /**
     * @IsGranted("CAN_SHOW_FICHE", subject="agent")
     */
    public function ficheAgent(Agent $agent) {

    }

    /**
     * @Route("/movie/{id}/delete")
     * @IsGranted("ROLE_ADMIN", subject="movie")
     *
     * @IsGranted("CAN_DELETE", subject="movie")
     */
    public function delete(Movie $movie, MovieRepository $movieRepository): Response
    {
        $movieRepository->remove($movie, true);

        $this->addFlash('success', 'The movie has been deleted.');

        return $this->redirectToRoute('app_movie_list');
    }

    /**
     * @Route("/movie/create", name="app_movie_create")
     * @IsGranted("ROLE_ADMIN")
     */
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(MovieType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $movie = $form->getData();
            $movie->setCreator($this->getUser());

            /** @var MovieRepository $movieRepository */
            $movieRepository = $doctrine->getRepository(Movie::class);
            $movieRepository->add($movie, true);

            $this->addFlash('success', 'The movie has been created.');

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('movie/create.html.twig', [
            'movie_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/load-movies", name="app_movie_load")
     */
    public function loadMovies(ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        /** @var MovieRepository $movieRepository */
        $movieRepository = $doctrine->getRepository(Movie::class);
        $movieRepository->removeAll();

        /** @var UserRepository $userRepository */
        $userRepository = $doctrine->getRepository(User::class);
        $userRepository->removeAll();

        /** @var GenreRepository $genreRepository */
        $genreRepository = $doctrine->getRepository(Genre::class);
        $genreRepository->removeAll();

        $user = new User();
        $user->setUsername('adrien');
        $user->setPassword($passwordHasher->hashPassword($user, 'adrien'));

        $userRepository->add($user, true);

        $user = new User();
        $user->setUsername('admin');
        $user->setPassword($passwordHasher->hashPassword($user, 'admin'));
        $user->setRoles(['ROLE_ADMIN']);

        $userRepository->add($user, true);

        $user = new User();
        $user->setUsername('john');
        $user->setPassword($passwordHasher->hashPassword($user, 'john'));
        $user->setRoles(['ROLE_ADMIN']);

        $userRepository->add($user, true);


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
