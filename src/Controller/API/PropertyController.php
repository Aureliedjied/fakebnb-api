<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\PropertyRepository;
use App\Entity\Property;
use App\Entity\Location;
use App\Entity\Category;
use App\Entity\Amenity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class PropertyController extends AbstractController
{
    private $propertyRepository;
    private $entityManager;
    private $validator;

    public function __construct(PropertyRepository $propertyRepository, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->propertyRepository = $propertyRepository;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    #[Route('/properties', methods: ['GET'])]
    public function getProperties(Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = 10;
        $properties = $this->propertyRepository->paginateProperties($page, $limit);
        $maxPage = ceil($properties->count() / $limit);
        return $this->json([
            'properties' => $properties,
            'maxPage' => $maxPage,
            'currentPage' => $page
        ], 200, [], ['groups' => 'property']);
    }

    #[Route('/properties/{slug}-{id}', methods: ['GET'])]
    public function getProperty(int $id): JsonResponse
    {
        $property = $this->propertyRepository->find($id);
        if (!$property) {
            return new JsonResponse(['error' => 'Property not found'], 404);
        }
        return $this->json($property, 200, [], ['groups' => 'property']);
    }


    #[Route('/properties', methods: ['POST'])]
    public function createProperty(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    
        $location = new Location();
        $location->setAddress($data['location']['address']);
        $location->setCity($data['location']['city']);
        $location->setCountry($data['location']['country']);
        $location->setLatitude($data['location']['latitude']);
        $location->setLongitude($data['location']['longitude']);
    
        $locationErrors = $validator->validate($location);
        if (count($locationErrors) > 0) {
            return new JsonResponse(['error' => (string) $locationErrors], 400);
        }
    
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
    
        $propertyErrors = $validator->validate($property);
        if (count($propertyErrors) > 0) {
            return new JsonResponse(['error' => (string) $propertyErrors], 400);
        }
    
        $this->entityManager->persist($location);
        $this->entityManager->persist($property);
        $this->entityManager->flush();
    
        return new JsonResponse(['status' => 'Property created'], 201);
    }
    


    #[Route('/properties/{id}', methods: ['PUT'])]
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

    #[Route('/properties/{id}', methods: ['DELETE'])]
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
