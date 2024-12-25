<?php

namespace App\Repository;

use App\Entity\Test;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Test::class);
    }

    public function save(Test $test, bool $flush = false): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($test);
        if ($flush) {
            $entityManager->flush();
        }
    }

    public function remove(Test $test, bool $flush = false): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($test);
        if ($flush) {
            $entityManager->flush();
        }
    }
}

