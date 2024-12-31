<?php

namespace App\Repository;

use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Property>
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }

    public function findAll(): array 
    { 
        return $this->createQueryBuilder('p') 
            ->getQuery() 
            ->getResult();
    }

    /**
     * Finds and returns the amenities associated with a given property ID.
     *
     * This method creates a query builder to select the names of amenities
     * associated with a property identified by the provided property ID.
     *
     * @param int $propertyId The ID of the property for which to find amenities.
     * @return array The list of amenities associated with the property.
     */
    public function findAmenitiesByPropertyId(int $propertyId)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('a.name')
            ->join('p.amenities', 'a')
            ->where('p.id = :id')
            ->setParameter('id', $propertyId);

        return $qb->getQuery()->getResult();
    }

}
