<?php

namespace App\Repository;

use App\Entity\VoteAnnonces;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VoteAnnonces|null find($id, $lockMode = null, $lockVersion = null)
 * @method VoteAnnonces|null findOneBy(array $criteria, array $orderBy = null)
 * @method VoteAnnonces[]    findAll()
 * @method VoteAnnonces[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoteAnnoncesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VoteAnnonces::class);
    }

    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('p');
    }

    /**
     * @return Vote[]
     */
    public function findUserVote(int $userId): array
    {
        $vote = $this->findVisibleQuery('p')
            ->where('p.userId = :user')
            ->setParameter('user', $userId)
            ->getQuery()
            ->getResult();
        return $vote;
    }

    /**
     * @return Vote[]
     */
    public function findCount(int $id)
    {
        $qb = $this->createQueryBuilder('p');

        return $qb
        ->select('count(p.id)')
        ->where('p.annoncesId = :ids')
        ->setParameter('ids', $id)
        ->getQuery()
        ->getSingleScalarResult();

    }
}
