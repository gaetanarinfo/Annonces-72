<?php

namespace App\Repository;

use App\Entity\Picture;
use App\Entity\Annonces;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Annonces|null find(Annonces[]$id, $lockMode = null, $lockVersion = null)
 * @method Annonces|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonces[]    findAll()
 * @method Annonces[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnoncesRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Annonces::class);
        $this->paginator = $paginator;
    }

    /**
     * @return PaginationInterface
     */
    public function paginateAllVisible(int $page): PaginationInterface
    {
        $query = $this->findVisibleQuery('p');

        $annonces = $this->paginator->paginate(
            $query
            ->orderBy('p.id', 'DESC')
            ->getQuery(),
            $page,
            12
        );


        $this->hydratePicture($annonces);

        return $annonces;
    }

    /**
     * @return PaginationInterface
     */
    public function paginateAllVisible2(int $page): PaginationInterface
    {
        $query = $this->findVisibleQuery('p');

        $annonces = $this->paginator->paginate(
            $query
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery(),
            $page,
            12
        );


        $this->hydratePicture($annonces);

        return $annonces;
    }

    /**
     * @return Annonces[]
     */
    public function findLatest(): array
    {
        $annonces = $this->findVisibleQuery('p')
            ->setMaxResults(6)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
        $this->hydratePicture($annonces);
        return $annonces;
    }

    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('p');
    }

    private function hydratePicture($annonces) {
        if (method_exists($annonces, 'getItems')) {
            $annonces = $annonces->getItems();
        }
        $pictures = $this->getEntityManager()->getRepository(Picture::class)->findForAnnonces($annonces);
        foreach($annonces as $annonce) {
            /** @var $annonce Annonces */
            if($pictures->containsKey($annonce->getId())) {
                $annonce->setPicture($pictures->get($annonce->getId()));
            }
        }
    }

    /**
     * @return Annonces[]
     */
    public function findAllAnnonces(string $username): array
    {
        $annonces = $this->findVisibleQuery('p')
            ->orderBy('p.id', 'DESC')
            ->where('p.createdBy = :username')
            ->setParameter('username', $username)
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
        $this->hydratePicture($annonces);
        return $annonces;
    }

    /**
     * @return Annonces[]
     */
    public function findCount(string $username)
    {
        $qb = $this->createQueryBuilder('p');

        return $qb
        ->select('count(p.id)')
        ->where('p.author = :username')
        ->setParameter(':username', $username)
        ->getQuery()
        ->getSingleScalarResult();

        $this->hydratePicture($qb);

    }

    /**
     * @return Annonces[]
     */
    public function findSimilaire(int $cat): array
    {
        $annonces = $this->findVisibleQuery('p')
            ->where('p.category = :cat')
            ->setParameter('cat', $cat)
            ->setMaxResults(4)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
        $this->hydratePicture($annonces);
        return $annonces;
    }
}
