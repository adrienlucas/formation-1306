<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateMovieTest extends WebTestCase
{
    public function testMovieCreationIsPossible(): void
    {
        $client = static::createClient();
        $client->request('GET', '/movie/create');

        $client->submitForm('Submit', [
            'movie[title]' => 'toto',
            'movie[description]' => 'Lorem ipsum',
            'movie[genre]' => 6,
        ]);

        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains(
            'div.alert-success',
            'The movie has been created.'
        );
    }

    public function testMovieCreationIsSecured(): void
    {
        $client = static::createClient();
        $client->request('GET', '/movie/create');

        $client->submitForm('Submit', [
            'movie[title]' => '',
            'movie[description]' => str_repeat('Lorem ipsum', 500),
        ]);

        $this->assertSelectorTextContains(
            'form',
            'This value should not be blank.'
        );
        $this->assertSelectorTextContains(
            'form',
            'This value is too long. It should have 1024 characters or less.'
        );
    }
}
