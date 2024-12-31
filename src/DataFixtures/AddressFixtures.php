<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Property;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AddressFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $address = new Address();
            $address->setAddress("123 Street $i")
                    ->setCity("City $i")
                    ->setCountry("Country $i")
                    ->setLatitude(mt_rand(-90, 90))
                    ->setLongitude(mt_rand(-180, 180));

            // Associer l'adresse à une propriété
            $property = $this->getReference("property_$i", Property::class);
            $address->setProperty($property);

            $manager->persist($address);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PropertyFixtures::class,
        ];
    }
}
