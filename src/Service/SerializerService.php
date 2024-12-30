<?php

namespace App\Service;

use Symfony\Component\Serializer\SerializerInterface;

class SerializerService
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function serialize($data, string $format = 'json', array $context = []): string
    {
        return $this->serializer->serialize($data, $format, $context);
    }

    public function deserialize(string $data, string $type, string $format = 'json', array $context = [])
    {
        return $this->serializer->deserialize($data, $type, $format, $context);
    }
}
