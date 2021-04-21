<?php

namespace App\Repository;

use App\Entity\LikeAnnonces;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LikeAnnonces|null find($id, $lockMode = null, $lockVersion = null)
 * @method LikeAnnonces|null findOneBy(array $criteria, array $orderBy = null)
 * @method LikeAnnonces[]    findAll()
 * @method LikeAnnonces[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikeAnnoncesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LikeAnnonces::class);
    }

    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('p');
    }

    /**
     * @return Like[]
     */
    public function findUserLike(int $userId): array
    {
        $vote = $this->findVisibleQuery('p')
            ->where('p.annoncesId = :user')
            ->setParameter('user', $userId)
            ->getQuery()
            ->getResult();
        return $vote;
    }

    /**
     * @return Like[]
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
