<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    /**
     * Récupère une question aléatoire parmi toutes les questions
     */
    public function findRandomQuestion(): ?Question
    {
        $count = $this->createQueryBuilder('q')
            ->select('COUNT(q.id)')
            ->getQuery()
            ->getSingleScalarResult();

        if (0 === $count) {
            return null;
        }

        return $this->createQueryBuilder('q')
            ->setFirstResult(random_int(0, $count - 1))
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Récupère une question aléatoire en excluant certaines IDs
     */
    public function findUnplayedQuestion(array $excludedIds): ?Question
    {
        if (empty($excludedIds)) {
            return $this->findRandomQuestion();
        }

        return $this->createQueryBuilder('q')
            ->where('q.id NOT IN (:excluded)')
            ->setParameter('excluded', $excludedIds)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
