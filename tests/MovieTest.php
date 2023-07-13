<?php

namespace App\Tests;

use App\Entity\Movie;
use App\Entity\User;
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
    private User $dummyUser;

    public function setUp(): void
    {
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $fixtureExecutor = new ORMExecutor($entityManager);
        $fixtureExecutor->setPurger(new ORMPurger($entityManager));

        $fixtureExecutor->execute([new class extends AbstractFixture {
            public function load(ObjectManager $manager)
            {
                $user = new User();
                $user->setUsername('user');
                $user->setPassword('fake_hash');
                $manager->persist($user);
                $this->addReference('dummy user', $user);

                $movie = new Movie();
                $movie->setTitle('Movie 1');
                $movie->setPlot('Movie 1');
                $movie->setReleasedAt(new \DateTime());
                $movie->setCreatedBy($user);
                $manager->persist($movie);

                $this->addReference('dummy movie', $movie);

                $movie = new Movie();
                $movie->setTitle('Movie 2');
                $movie->setPlot('Movie 2');
                $movie->setReleasedAt(new \DateTime());
                $movie->setCreatedBy($user);
                $manager->persist($movie);

                $manager->flush();
            }
        }]);

        $this->dummyMovie = $fixtureExecutor->getReferenceRepository()->getReference('dummy movie');
        $this->dummyUser = $fixtureExecutor->getReferenceRepository()->getReference('dummy user');
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

    public function testItDeniesAccessToMovieCreationWhenNotAuthenticated()
    {
        $client = static::createClient();
        $client->request('GET', '/movies/create');

        $this->assertResponseStatusCodeSame(302, 'The client was redirected to the login page');
        $this->assertResponseHeaderSame('Location', '/login');
    }

    public function testItCanCreateANewMovie()
    {
        $client = static::createClient();
        $client->loginUser($this->dummyUser);

        $client->request('GET', '/movies/create');
        $this->assertResponseIsSuccessful();

        $client->submitForm('Create', [
            'movie[title]' => 'dummy movie',
            'movie[plot]' => 'dummy plot',
            'movie[releasedAt]' => '2023-07-20',
        ]);

        $client->followRedirect();

        $this->assertSelectorTextSame('div.alert-success', 'Your movie has been created successfully.');
    }
}
