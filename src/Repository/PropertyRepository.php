<?php

namespace App\Repository;

use App\Entity\Property;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Property>
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
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

    public function paginateProperties(int $page, int $limit): Paginator
    {
        return new Paginator($this
            ->createQueryBuilder('p')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->setHint(Paginator::HINT_ENABLE_DISTINCT, false)
        );
    }

}
