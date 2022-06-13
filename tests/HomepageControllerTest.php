<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomepageControllerTest extends WebTestCase
{
    public function testHomepageIsWellDisplayed(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello world');
    }

    public function testItSaysHelloToAdrien(): void
    {
        $client = static::createClient();
        $client->request('GET', '/hello/adrien');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello adrien');
    }
}
