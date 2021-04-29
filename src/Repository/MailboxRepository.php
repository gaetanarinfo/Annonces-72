<?php

namespace App\Repository;

use App\Entity\Mailbox;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Mailbox|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mailbox|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mailbox[]    findAll()
 * @method Mailbox[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MailboxRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Mailbox::class);
        $this->paginator = $paginator;
    }

   /**
     * @return PaginationInterface
     */
    public function paginateAllVisible(string $username, int $page): PaginationInterface
    {
        $query = $this->findVisibleQuery('p');

        $mailbox = $this->paginator->paginate(
            $query
            ->where('p.isRead = :read')
            ->andWhere('p.recipient = :recipient')
            ->setParameter('recipient', $username)
            ->setParameter(':read', 0)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery(),
            $page,
            25
        );

        return $mailbox;
    }

    /**
     * @return PaginationInterface
     */
    public function paginateAllVisibleArchive(string $username, int $page): PaginationInterface
    {
        $query = $this->findVisibleQuery('p');

        $mailbox = $this->paginator->paginate(
            $query
            ->where('p.isRead = :read')
            ->andWhere('p.recipient = :recipient')
            ->setParameter('recipient', $username)
            ->setParameter(':read', 1)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery(),
            $page,
            25
        );

        return $mailbox;
    }

    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('p');
    }

    /**
     * @return Mailbox[]
     */
    public function findCount(string $username)
    {
        $qb = $this->createQueryBuilder('p');

        return $qb
        ->select('count(p.id)')
        ->where('p.recipient = :recipient')
        ->andWhere('p.isRead = :read')
        ->setParameter('recipient', $username)
        ->setParameter('read', 0)
        ->getQuery()
        ->getSingleScalarResult();

    }
}
