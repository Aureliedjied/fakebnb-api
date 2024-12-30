<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\SerializerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController
{
    private $serializerService;

    public function __construct(SerializerService $serializerService)
    {
        $this->serializerService = $serializerService;
    }

    #[Route('/api/users', methods: ['GET'])]
    public function index(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();
        $jsonContent = $this->serializerService->serialize($users);
        return new JsonResponse($jsonContent, JsonResponse::HTTP_OK, [], true);
    }

    #[Route('/api/users', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = new User();
        $user->setUsername($data['username']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']); 

        $em->persist($user);
        $em->flush();

        $jsonContent = $this->serializerService->serialize($user);
        return new JsonResponse($jsonContent, JsonResponse::HTTP_CREATED, [], true);
    }

    // TODO : ajouter les méthodes pour DELETE, etc.
}
