<?php
declare(strict_types=1);

namespace App\Gateway;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbGateway
{
    private $httpClient;
    private $apiKey = 'e0ded5e2';

    public function __construct(
        HttpClientInterface $httpClient
    )
    {
        $this->httpClient = $httpClient;
    }

    public function getPosterByMovieTitle(string $title): string
    {
        $url = sprintf(
            'https://www.omdbapi.com/?apikey=%s&t=%s',
            $this->apiKey,
            $title
        );

        $response = $this->httpClient->request('GET', $url);

        return $response->toArray()['Poster'];
    }
}