<?php

namespace App\Controller\API;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/api')]
class AuthController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private UserPasswordHasherInterface $passwordHasher,
        private Security $security
    ) {}

    #[Route('/test', name: 'api_test')]
    public function test(Request $request): JsonResponse
    {
        return $this->json([
            'authorization' => $request->headers->get('Authorization'),
            'all_headers' => array_map(
                fn($header) => $header[0],
                $request->headers->all()
            )
        ]);
    }

    #[Route('/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            
            $user = new User();
            $user->setEmail($data['email']);
            $user->setFirstName($data['firstName']);
            $user->setLastName($data['lastName']);
            $user->setPhoneNumber($data['phoneNumber']);
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, $data['password'])
            );
            // Ajouter le rôle par défaut
            $user->setRoles(['ROLE_USER']);

            $errors = $this->validator->validate($user);
            if (count($errors) > 0) {
                return $this->json(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
            }

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->json(['message' => 'User registered successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json([
                'error' => 'Registration failed',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }


    #[Route('/me', name: 'api_user_me', methods: ['GET'])]
    public function getMe(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'User not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->json($user, JsonResponse::HTTP_OK, [], ['groups' => 'user:read']);
    }
}
