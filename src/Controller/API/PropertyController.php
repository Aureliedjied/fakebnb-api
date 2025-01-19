<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\PropertyRepository;
use App\Entity\Property;
use App\Entity\Location;
use App\Entity\Category;
use App\Entity\Amenity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    private $propertyRepository;
    private $entityManager;

    public function __construct(PropertyRepository $propertyRepository, EntityManagerInterface $entityManager)
    {
        $this->propertyRepository = $propertyRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/api/properties', methods: ['GET'])]
    public function getProperties(): JsonResponse
    {
        $properties = $this->propertyRepository->findAll();
        return $this->json($properties, 200, [], ['groups' => 'property']);
    }

    #[Route('/api/properties/{slug}-{id}', methods: ['GET'])]
    public function getProperty(int $id): JsonResponse
    {
        $property = $this->propertyRepository->find($id);
        if (!$property) {
            return new JsonResponse(['error' => 'Property not found'], 404);
        }
        return $this->json($property, 200, [], ['groups' => 'property']);
    }


    #[Route('/api/properties', methods: ['POST'])]
    public function createProperty(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $location = new Location();
        $location->setAddress($data['location']['address']);
        $location->setCity($data['location']['city']);
        $location->setCountry($data['location']['country']);
        $location->setLatitude($data['location']['latitude']);
        $location->setLongitude($data['location']['longitude']);

        $category = $this->entityManager->getRepository(Category::class)->find($data['category_id']);
        if (!$category) {
            return new JsonResponse(['error' => 'Category not found'], 404);
        }

        $property = new Property();
        $property->setTitle($data['title']);
        $property->setDescription($data['description']);
        $property->setPricePerNight($data['pricePerNight']);
        $property->setSlug($data['slug']);
        $property->setLocation($location);
        $property->setBedrooms($data['bedrooms']);
        $property->setCategory($category);
        $property->setBreakfastIncluded($data['breakfastIncluded']);

        foreach ($data['amenities'] as $amenityId) {
            $amenity = $this->entityManager->getRepository(Amenity::class)->find($amenityId);
            if ($amenity) {
                $property->addAmenity($amenity);
            }
        }

        $this->entityManager->persist($location);
        $this->entityManager->persist($property);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Property created'], 201);
    }

    #[Route('/api/properties/{id}', methods: ['PUT'])]
    public function editProperty(int $id, Request $request): JsonResponse
    {
        $property = $this->propertyRepository->find($id);
        if (!$property) {
            return new JsonResponse(['error' => 'Property not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['title'])) {
            $property->setTitle($data['title']);
        }
        if (isset($data['description'])) {
            $property->setDescription($data['description']);
        }
        if (isset($data['pricePerNight'])) {
            $property->setPricePerNight($data['pricePerNight']);
        }
        if (isset($data['slug'])) {
            $property->setSlug($data['slug']);
        }
        if (isset($data['bedrooms'])) {
            $property->setBedrooms($data['bedrooms']);
        }
        if (isset($data['breakfastIncluded'])) {
            $property->setBreakfastIncluded($data['breakfastIncluded']);
        }

        if (isset($data['location'])) {
            $location = $property->getLocation();
            $location->setAddress($data['location']['address']);
            $location->setCity($data['location']['city']);
            $location->setCountry($data['location']['country']);
            $location->setLatitude($data['location']['latitude']);
            $location->setLongitude($data['location']['longitude']);
            $this->entityManager->persist($location);
        }

        if (isset($data['category_id'])) {
            $category = $this->entityManager->getRepository(Category::class)->find($data['category_id']);
            if ($category) {
                $property->setCategory($category);
            }
        }

        if (isset($data['amenities'])) {
            $property->getAmenities()->clear();
            foreach ($data['amenities'] as $amenityId) {
                $amenity = $this->entityManager->getRepository(Amenity::class)->find($amenityId);
                if ($amenity) {
                    $property->addAmenity($amenity);
                }
            }
        }

        $this->entityManager->persist($property);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Property updated'], 200);
    }

    #[Route('/api/properties/{id}', methods: ['DELETE'])]
    public function deleteProperty(int $id): JsonResponse
    {
        $property = $this->propertyRepository->find($id);
        if (!$property) {
            return new JsonResponse(['error' => 'Property not found'], 404);
        }

        $this->entityManager->remove($property);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Property deleted'], 200);
    }
}
