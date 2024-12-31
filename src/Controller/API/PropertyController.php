<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\PropertyRepository;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    private $propertyRepository;

    public function __construct(PropertyRepository $propertyRepository)
    {
        $this->propertyRepository = $propertyRepository;
    }

    #[Route('/api/properties', methods: ['GET'])]
    public function getProperties(PropertyRepository $propertyRepository) 
    {
        $properties = $propertyRepository->findAll(); 
        return $this->json($properties, 200, [], [
            'groups' => 'property']);
    }

    #[Route('/api/properties/{id}', methods: ['GET'])]
    public function getProperty(int $id, PropertyRepository $propertyRepository) 
    {
        $property = $propertyRepository->find($id); 
        return $this->json($property, 200, [], [
            'groups' => 'property']);
    }

}
