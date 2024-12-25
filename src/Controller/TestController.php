<?php

namespace App\Controller;

use App\Entity\Test;
use Symfony\UX\Map\Map;
use Symfony\UX\Map\Point;
use App\Repository\TestRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{
    #[Route('/', name: 'app_test')]
    public function index(): Response
    {
        $map = (new Map())
            ->center(new Point(46.903354, 1.888334))
            ->zoom(6)
            ->fitBoundsToMarkers()
        ;

        return $this->render('test/index.html.twig', [
            'map' => $map,
        ]);
    }

    #[Route('/api/tests', name: 'get_tests', methods: ['GET'])] 
    public function getTests(TestRepository $testRepository): JsonResponse 
    { 
        $tests = $testRepository->findAll(); 
        
        return $this->json($tests); 
    }
}
