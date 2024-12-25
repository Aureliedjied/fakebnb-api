<?php

namespace App\DataFixtures;

use App\Entity\Test;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // create 20 test! Bam!
        for ($i = 0; $i < 20; $i++) {
            $test = new Test();
            $test->setName('test '.$i);
            $manager->persist($test);
        }

        $manager->flush();
    }
}
