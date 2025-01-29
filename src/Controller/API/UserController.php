<?php
// src/Controller/Api/UserController.php
namespace App\Controller\API;

use App\Entity\User;
use App\Repository\UserRepository;
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
class UserController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private UserPasswordHasherInterface $passwordHasher,
        private Security $security
    ) {}


    #[Route('/users', name: 'api_users_get', methods: ['GET'])]
    public function getUsers(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();
        $data = $this->serializer->serialize($users, 'json', ['groups' => 'user:read']);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }


    #[Route('/users/{id}', name: 'api_user_get', methods: ['GET'])]
    public function getUserById(User $user): JsonResponse
    {
        return $this->json($user, Response::HTTP_OK, [], ['groups' => 'user:read']);
    }


    #[Route('/users/{id}', name: 'api_user_update', methods: ['PUT'])]
    public function updateUser(Request $request, User $user): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['firstName'])) {
            $user->setFirstName($data['firstName']);
        }
        if (isset($data['lastName'])) {
            $user->setLastName($data['lastName']);
        }
        if (isset($data['phoneNumber'])) {
            $user->setPhoneNumber($data['phoneNumber']);
        }
        if (isset($data['bio'])) {
            $user->setBio($data['bio']);
        }

        $this->entityManager->flush();

        return $this->json($user, Response::HTTP_OK, [], ['groups' => 'user:read']);
    }


    #[Route('/users/promote/{id}', name: 'api_user_promote_host', methods: ['PATCH'])]
    public function promoteToHost(User $user): JsonResponse
    {
        $roles = $user->getRoles();
        if (!in_array('ROLE_HOST', $roles)) {
            $roles[] = 'ROLE_HOST';
            $user->setRoles($roles);
            $this->entityManager->flush();
        }

        return $this->json(['message' => 'User promoted to host successfully']);
    }

}
