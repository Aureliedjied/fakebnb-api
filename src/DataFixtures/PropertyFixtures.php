<?php

namespace App\DataFixtures;

use App\Entity\Property;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PropertyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $property = new Property();
            $property->setTitle("Property $i")
                     ->setDescription("Description for Property $i")
                     ->setPricePerNight(mt_rand(50, 300));
            $manager->persist($property);

            // Ajouter une référence pour cette propriété
            $this->addReference("property_$i", $property);
        }

        $manager->flush();
    }
}
