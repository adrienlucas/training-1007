<?php

namespace App\Tests;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MovieTest extends WebTestCase
{
    private Movie $dummyMovie;

    public function setUp(): void
    {
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $fixtureExecutor = new ORMExecutor($entityManager);
        $fixtureExecutor->setPurger(new ORMPurger($entityManager));

        $fixtureExecutor->execute([new class extends AbstractFixture {
            public function load(ObjectManager $manager)
            {
                $movie = new Movie();
                $movie->setTitle('Movie 1');
                $movie->setPlot('Movie 1');
                $movie->setReleasedAt(new \DateTime());
                $manager->persist($movie);

                $this->addReference('dummy movie', $movie);

                $movie = new Movie();
                $movie->setTitle('Movie 2');
                $movie->setPlot('Movie 2');
                $movie->setReleasedAt(new \DateTime());
                $manager->persist($movie);

                $manager->flush();
            }
        }]);

        $this->dummyMovie = $fixtureExecutor->getReferenceRepository()->getReference('dummy movie');
        self::ensureKernelShutdown();
    }

    public function testItDisplaysMovies(): void
    {
        $client = static::createClient();
        $client->request('GET', '/movies');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorCount(2, 'ul.movies li');
    }

    public function testItDisplayTheDetailsOfAMovie()
    {
        $client = static::createClient();
        $client->request('GET', '/movies/detail/'.$this->dummyMovie->getId());

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', $this->dummyMovie->getTitle());
        $this->assertSelectorTextSame('p.plot', $this->dummyMovie->getPlot());
        $this->assertSelectorTextContains(
            'p.releasedAt',
            $this->dummyMovie->getReleasedAt()->format('Y-m-d')
        );
    }

    public function testItCanCreateANewMovie()
    {
        // $client->submitForm([...])
    }
}
