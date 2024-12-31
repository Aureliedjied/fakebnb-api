<?php

namespace App\Controller\API;

use App\Repository\AmenityRepository;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


class AmenetiesController extends AbstractController
{
    private $amenityRepository;
    private $propertyRepository;

    public function __construct(AmenityRepository $amenityRepository, PropertyRepository $propertyRepository)
    {
        $this->amenityRepository = $amenityRepository;
        $this->propertyRepository = $propertyRepository;
    }

    #[Route('/api/amenities', methods: ['GET'])]
    public function getAmenities(): JsonResponse
    {
        $amenities = $this->amenityRepository->findAll();
        return new JsonResponse($amenities);
    }

    #[Route('/api/properties/{id}/amenities', methods: ['GET'])]
    public function getAmenitiesByProperty(int $id): JsonResponse
    {
        $amenities = $this->propertyRepository->findAmenitiesByPropertyId($id);
        return new JsonResponse($amenities);
    }

    #[Route('/api/amenities/{id}', methods: ['GET'])]
    public function getAmenityById(int $id): JsonResponse
    {
        $amenity = $this->amenityRepository->find($id);

        return new JsonResponse($amenity);
    }
}