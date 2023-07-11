<?php

namespace App\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomepageTest extends WebTestCase
{
    public static function provideHelloSayingPages(): array
    {
        return [
            'Homepage' => ['/', 'World'],
            'Hello page' => ['/hello/adrien', 'adrien'],
        ];
    }

    /**
     * @group e2e
     * @dataProvider provideHelloSayingPages
     */
    public function testTheApplicationSaysHello(string $uri, string $expectedName): void
    {
        $client = static::createClient();
        $client->request('GET', $uri);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello '.$expectedName);
    }

    /**
     * @group controversial
     */
    public function testTheHelloPageIsNotFoundWhenNameIsNotGiven(): void
    {
        $client = static::createClient();
        $client->request('GET', '/hello');

        $this->assertResponseStatusCodeSame(404);
    }
}

// make:controller MovieController
// Edit the created MovieController to define an array of movies
//     $movies = ['The Matrix', 'Indiana Jones'];
// Inject the movie list inside the view
// Edit the created movie/index.html.twig template to loop over the movies
//      {% for movie in movies %}

// make:test MovieTest
// public function testItDisplayMovies
// > assert there are 2 movies
