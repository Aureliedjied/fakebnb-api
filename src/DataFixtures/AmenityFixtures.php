<?php

namespace App\DataFixtures;

use App\Entity\Amenity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AmenityFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $amenities = ['Piscine', 'Wi-Fi', 'Parking', 'Salle de sport'];

        foreach ($amenities as $name) {
            $amenity = new Amenity();
            $amenity->setName($name);
            $manager->persist($amenity);
        }

        $manager->flush();
    }
}
