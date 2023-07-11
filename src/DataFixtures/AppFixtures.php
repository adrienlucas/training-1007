<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $actionGenre = new Genre();
        $manager->persist($actionGenre);
        $actionGenre->setName('Action');

        $comedyGenre = new Genre();
        $manager->persist($comedyGenre);
        $comedyGenre->setName('Comedy');

        $dramaGenre = new Genre();
        $manager->persist($dramaGenre);
        $dramaGenre->setName('Drama');

        $movie1 = new Movie();
        $manager->persist($movie1);
        $movie1->setTitle('Avengers: Endgame');
        $movie1->setPlot('After the devastating events of Avengers: Infinity War, the universe is in ruins. With the help of remaining allies, the Avengers assemble once more in order to reverse Thanos\' actions and restore balance to the universe.');
        $movie1->setReleasedAt(new \DateTime('2019-04-26'));

        $actionGenre->addMovie($movie1);

        $movie2 = new Movie();
        $manager->persist($movie2);
        $movie2->setTitle('Jumanji: Welcome to the Jungle');
        $movie2->setPlot('Four teenagers are sucked into a magical video game, and the only way they can escape is to work together to finish the game.');
        $movie2->setReleasedAt(new \DateTime('2017-12-20'));

        $actionGenre->addMovie($movie2);


        $movie3 = new Movie();
        $manager->persist($movie3);
        $movie3->setTitle('Deadpool');
        $movie3->setPlot('A fast-talking mercenary with a morbid sense of humor is subjected to a rogue experiment that leaves him with accelerated healing powers and a quest for revenge.');
        $movie3->setReleasedAt(new \DateTime('2016-02-12'));

        $comedyGenre->addMovie($movie3);

        $movie4 = new Movie();
        $manager->persist($movie4);
        $movie4->setTitle('Superbad');
        $movie4->setPlot('Two co-dependent high school seniors are forced to deal with separation anxiety after their plan to stage a booze-soaked party goes awry.');
        $movie4->setReleasedAt(new \DateTime('2007-08-17'));

        $comedyGenre->addMovie($movie4);
        $dramaGenre->addMovie($movie4);

        $movie5 = new Movie();
        $manager->persist($movie5);
        $movie5->setTitle('The Shawshank Redemption');
        $movie5->setPlot('Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.');
        $movie5->setReleasedAt(new \DateTime('1994-10-14'));

        $dramaGenre->addMovie($movie5);

        $manager->flush();
    }
}
