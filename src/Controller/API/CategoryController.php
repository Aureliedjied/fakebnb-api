<?php

namespace App\Controller\API;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class CategoryController extends AbstractController
{

    private $categoryRepository;
    private $entityManager;


    public function __construct(CategoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        $this->categoryRepository = $categoryRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/categories', methods: ['GET'])]
    public function getcategories(): JsonResponse
    {
        $categories = $this->categoryRepository->findAll();
        return $this->json($categories, 200, [], ['groups' => 'category']);
    }

    #[Route('/categories/{id}', methods: ['GET'])]
    public function getcategory(int $id): JsonResponse
    {
        $category = $this->categoryRepository->find($id);
        if (!$category) {
            return $this->json(['error' => 'Category not found'], 404);
        }
        return $this->json($category, 200, [], ['groups' => 'category']);
    }

    #[Route('/categories', methods: ['POST'])]
    public function createCategory(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $category = new Category();
        $category->setName($data['name']);

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $this->json(['status' => 'Category created'], 201);
    }

    #[Route('/categories/{id}', methods: ['PUT'])]
    public function editCategory(int $id, Request $request): JsonResponse
    {
        $category = $this->categoryRepository->find($id);
        if (!$category) {
            return $this->json(['error' => 'Category not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $category->setName($data['name']);

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $this->json(['status' => 'Category updated'], 200);
    }

    #[Route('/categories/{id}', methods: ['DELETE'])]
    public function deleteCategory(int $id): JsonResponse
    {
        $category = $this->categoryRepository->find($id);
        if (!$category) {
            return $this->json(['error' => 'Category not found'], 404);
        }

        $this->entityManager->remove($category);
        $this->entityManager->flush();

        return $this->json(['status' => 'Category deleted'], 200);
    }

}
