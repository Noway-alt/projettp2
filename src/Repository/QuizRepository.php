<?php

namespace App\Repository;

use App\Entity\Quiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class QuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quiz::class);
    }

    public function search(?string $query): array
    {
        if (!$query) {
            return $this->findAll();
        }

        return $this->createQueryBuilder('q')
            ->where('q.title LIKE :query')
            ->orWhere('q.description LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('q.title', 'ASC')
            ->getQuery()
            ->getResult();
    }
}