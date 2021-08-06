<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();

        $user
            ->setUsername('will')
            ->setPassword('$argon2id$v=19$m=65536,t=4,p=1$EW72GB2wlYlmH6WFhK199w$U/med7Ds8DP9zyib6iH6+SBNHQ8JYCfyTq54I2bTwuw');

        $manager->persist($user);

        $manager->flush();
    }
}
