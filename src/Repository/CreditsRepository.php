<?php

namespace App\Repository;

use App\Entity\Credits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Credits|null find($id, $lockMode = null, $lockVersion = null)
 * @method Credits|null findOneBy(array $criteria, array $orderBy = null)
 * @method Credits[]    findAll()
 * @method Credits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CreditsRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Credits::class);
        $this->paginator = $paginator;
    }

        /**
     * @return PaginationInterface
     */
    public function paginateAllVisible(string $userId, int $page): PaginationInterface
    {
        $query = $this->findVisibleQuery('p');

        $credits = $this->paginator->paginate(
            $query
            ->where('p.id = :user')
            ->setParameter('user', $userId)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery(),
            $page,
            12
        );

        return $credits;
    }

    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('p');
    }

}
