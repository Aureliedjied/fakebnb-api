<?php

namespace App\Controller\API;

use App\Repository\AddressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AddressController extends AbstractController
{
    private $addressRepository;

    public function __construct(AddressRepository $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }

    #[Route('/api/addresses', name: 'app_addresses')]
    public function getAddresses(): JsonResponse
    {
        $addresses = $this->addressRepository->findAll();
        $addressData = [];

        foreach ($addresses as $address) {
            $addressData[] = [
                'fullAddress' => $address->getAddress(),
                'latitude' => $address->getLatitude(),
                'longitude' => $address->getLongitude(),
            ];
        }

        return new JsonResponse($addressData);
    }
}
