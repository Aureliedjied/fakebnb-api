<?php

namespace App\Controller\API;

use App\Repository\PropertyRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController
{
    private $propertyRepository;

    public function __construct(PropertyRepository $propertyRepository)
    {
        $this->propertyRepository = $propertyRepository;
    }

    #[route('/api/properties/{id}', methods: ['GET'])]
    public function getProperty(int $id): JsonResponse
    {
        $property = $this->propertyRepository->find($id);
        return new JsonResponse($property);
    }

    #[Route('/api/properties', methods: ['GET'])]
    public function getProperties(): JsonResponse
    {
        $properties = $this->propertyRepository->findAll();
        return new JsonResponse($properties);
    }

    #[Route('/api/properties/{id}/amenities', methods: ['GET'])]
    public function getAmenities(int $id): JsonResponse
    {
        $amenities = $this->propertyRepository->findAmenitiesByPropertyId($id);
        return new JsonResponse($amenities);
    }
}
