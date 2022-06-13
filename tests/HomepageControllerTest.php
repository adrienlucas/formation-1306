<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomepageControllerTest extends WebTestCase
{
    /**
     * @dataProvider provideUrlToTest
     */
    public function testPageIsWellDisplayed(string $url, string $expectedText): void
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $expectedText);
    }

    public function provideUrlToTest()
    {
        return [
            'Homepage' => ['/', 'Hello world'],
            'Hello adrien' => ['/hello/adrien', 'Hello adrien'],
            'Hello toto' => ['/hello/toto', 'Hello toto'],
            'Hello by default' => ['/hello', 'Hello world'],
        ];
    }
}
