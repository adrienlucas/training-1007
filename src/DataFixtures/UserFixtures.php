<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // set the hashed password for each User
        // find a way to hash passwords (with a service)
            // debug:container + debug:autowiring
        // inject the service in __construct & use it here

        // POKE ME IN THE CHAT WHEN IT'S DONE

        $manager->flush();
    }
}
