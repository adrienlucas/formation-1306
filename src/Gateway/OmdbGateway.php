<?php
declare(strict_types=1);

namespace App\Gateway;

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
}