<?php

namespace App\DataFixtures;

use App\Entity\Property;
use App\Entity\Location;
use App\Entity\Amenity;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PropertyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Créer des catégories
        $categoriesList = [];
        $categoriesNames = ['Appartement', 'Villa', 'Bungalow', 'Chambre'];
        foreach ($categoriesNames as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
            $categoriesList[] = $category;
        }

        // Créer des amenities
        $amenitiesList = [];
        $amenitiesNames = ['WiFi', 'Pool', 'Air Conditioning', 'Parking'];
        foreach ($amenitiesNames as $amenityName) {
            $amenity = new Amenity();
            $amenity->setName($amenityName);
            $manager->persist($amenity);
            $amenitiesList[] = $amenity;
        }

        // Créer des propriétés
        for ($i = 1; $i <= 10; $i++) {
            $location = new Location();
            $location->setAddress("123 Street $i")
                     ->setCity("City $i")
                     ->setCountry("Country $i")
                     ->setLatitude($faker->latitude)
                     ->setLongitude($faker->longitude);

            $manager->persist($location);

            $property = new Property();
            $property->setTitle("Property $i")
                     ->setSlug("property-$i")
                     ->setDescription("Description for Property $i")
                     ->setPricePerNight(mt_rand(30, 300))
                     ->setLocation($location)
                     ->setBedrooms($faker->numberBetween(1, 5))
                     ->setCategory($categoriesList[array_rand($categoriesList)])
                     ->setBreakfastIncluded($faker->boolean);

            // Ajouter des amenities à la propriété
            for ($j = 0; $j < mt_rand(1, 4); $j++) {
                $property->addAmenity($amenitiesList[array_rand($amenitiesList)]);
            }

            $manager->persist($property);
        }

        $manager->flush();
    }
}

