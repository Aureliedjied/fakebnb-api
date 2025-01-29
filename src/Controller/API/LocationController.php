<?php

namespace App\Controller\API;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\LocationRepository;
use App\Entity\Location;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class LocationController extends AbstractController
{
    private $locationRepository;
    private $entityManager;

    public function __construct(LocationRepository $locationRepository, EntityManagerInterface $entityManager)
    {
        $this->locationRepository = $locationRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/locations', methods: ['GET'])]
    public function getLocations(): JsonResponse
    {
        $locations = $this->locationRepository->findAll();
        return $this->json($locations, 200, [], ['groups' => 'location']);
    }

    #[Route('/api/locations/{id}', methods: ['GET'])]
    public function getLocation(int $id): JsonResponse
    {
        $location = $this->locationRepository->find($id);
        if (!$location) {
            return new JsonResponse(['error' => 'Location not found'], 404);
        }
        return $this->json($location, 200, [], ['groups' => 'location']);
    }

    #[Route('/locations', methods: ['POST'])]
    public function createLocation(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $location = new Location();
        $location->setAddress($data['address']);
        $location->setCity($data['city']);
        $location->setCountry($data['country']);
        $location->setLatitude($data['latitude']);
        $location->setLongitude($data['longitude']);

        $this->entityManager->persist($location);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Location created'], 201);
    }

    #[Route('/locations/{id}', methods: ['PUT'])]
    public function editLocation(int $id, Request $request): JsonResponse
    {
        $location = $this->locationRepository->find($id);
        if (!$location) {
            return new JsonResponse(['error' => 'Location not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['address'])) {
            $location->setAddress($data['address']);
        }
        if (isset($data['city'])) {
            $location->setCity($data['city']);
        }
        if (isset($data['country'])) {
            $location->setCountry($data['country']);
        }
        if (isset($data['latitude'])) {
            $location->setLatitude($data['latitude']);
        }
        if (isset($data['longitude'])) {
            $location->setLongitude($data['longitude']);
        }

        $this->entityManager->persist($location);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Location updated'], 200);
    }

    #[Route('/locations/{id}', methods: ['DELETE'])]
    public function deleteLocation(int $id): JsonResponse
    {
        $location = $this->locationRepository->find($id);
        if (!$location) {
            return new JsonResponse(['error' => 'Location not found'], 404);
        }

        $this->entityManager->remove($location);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Location deleted'], 200);
    }
}
