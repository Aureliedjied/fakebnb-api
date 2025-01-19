<?php

namespace App\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PaginationNormalizer implements NormalizerInterface
{
    // TODO: Implement the normalize method
    public function normalize(mixed $object, ?string $format = null, array $context = []): 
        array|string|int|float|bool|\ArrayObject|null
    {
        // Implement your normalization logic here
        return []; 
    }

    // TODO: Implement the supportsNormalization method
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        // Example logic to check if the given data can be normalized by this normalizer
        return is_object($data);
    }
    public function getSupportedTypes(?string $format): array
    {
        return ['array'];
    }

}
