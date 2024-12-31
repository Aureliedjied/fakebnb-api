<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\AmenityRepository;
use App\Entity\Amenity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AmenityController extends AbstractController
{
    private $amenityRepository;
    private $entityManager;

    public function __construct(AmenityRepository $amenityRepository, EntityManagerInterface $entityManager)
    {
        $this->amenityRepository = $amenityRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/api/amenities', methods: ['GET'])]
    public function getAmenities(): JsonResponse
    {
        $amenities = $this->amenityRepository->findAll();
        return $this->json($amenities, 200, [], ['groups' => 'amenity']);
    }

    #[Route('/api/amenities/{id}', methods: ['GET'])]
    public function getAmenity(int $id): JsonResponse
    {
        $amenity = $this->amenityRepository->find($id);
        if (!$amenity) {
            return new JsonResponse(['error' => 'Amenity not found'], 404);
        }
        return $this->json($amenity, 200, [], ['groups' => 'amenity']);
    }

    #[Route('/api/amenities', methods: ['POST'])]
    public function createAmenity(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $amenity = new Amenity();
        $amenity->setName($data['name']);

        $this->entityManager->persist($amenity);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Amenity created'], 201);
    }

    #[Route('/api/amenities/{id}', methods: ['PUT'])]
    public function editAmenity(int $id, Request $request): JsonResponse
    {
        $amenity = $this->amenityRepository->find($id);
        if (!$amenity) {
            return new JsonResponse(['error' => 'Amenity not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $amenity->setName($data['name']);
        }
        if (isset($data['description'])) {
            $amenity->setDescription($data['description']);
        }

        $this->entityManager->persist($amenity);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Amenity updated'], 200);
    }

    #[Route('/api/amenities/{id}', methods: ['DELETE'])]
    public function deleteAmenity(int $id): JsonResponse
    {
        $amenity = $this->amenityRepository->find($id);
        if (!$amenity) {
            return new JsonResponse(['error' => 'Amenity not found'], 404);
        }

        $this->entityManager->remove($amenity);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Amenity deleted'], 200);
    }
}
