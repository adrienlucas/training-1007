<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MovieTest extends WebTestCase
{
    public function testItDisplaysMovies(): void
    {
        $client = static::createClient();
        $client->request('GET', '/movies');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorCount(2, 'ul li');
    }
}
