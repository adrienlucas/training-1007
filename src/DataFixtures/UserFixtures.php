<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $plainPassword = '123456';
        $hashedPassword = $this->hasher->hashPassword(
            $user,
            $plainPassword,
        );
        $user->setPassword($hashedPassword);
        $user->setUsername('user1');
        $this->addReference('default user', $user);

        $manager->persist($user);

        $user = new User();
        $plainPassword = 'azerty';
        $hashedPassword = $this->hasher->hashPassword(
            $user,
            $plainPassword,
        );
        $user->setPassword($hashedPassword);
        $user->setUsername('user2');

        $manager->persist($user);

        $user = new User();
        $plainPassword = 'admin';
        $hashedPassword = $this->hasher->hashPassword(
            $user,
            $plainPassword,
        );
        $user->setPassword($hashedPassword);
        $user->setUsername('admin');
        $user->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);

        $manager->flush();
    }
}
