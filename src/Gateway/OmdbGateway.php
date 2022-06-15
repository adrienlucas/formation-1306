<?php
declare(strict_types=1);

namespace App\Gateway;

use App\Entity\Movie;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbGateway
{
    private $httpClient;
    private $omdbApiKey;

    public function __construct(
        HttpClientInterface $httpClient,
        string $omdbApiKey
    )
    {
        $this->httpClient = $httpClient;
        $this->omdbApiKey = $omdbApiKey;
    }

    public function getPosterByMovieTitle(string $title): string
    {
        $url = sprintf(
            'https://www.omdbapi.com/?apikey=%s&t=%s',
            $this->omdbApiKey,
            $title
        );

        $response = $this->httpClient->request('GET', $url);

        return $response->toArray()['Poster'];
    }

    public function getMovieByTitle(string $title): Movie
    {
        $url = sprintf(
            'https://www.omdbapi.com/?apikey=%s&t=%s',
            $this->omdbApiKey,
            $title
        );

        $response = $this->httpClient->request('GET', $url);
        $movieData = $response->toArray();

        if (array_key_exists('Error', $movieData)) {
            throw new MovieNotFoundException();
        }

        $movie = new Movie();
        $movie->setTitle($movieData['Title']);
        $movie->setDescription($movieData['Plot']);

        return $movie;
    }
}