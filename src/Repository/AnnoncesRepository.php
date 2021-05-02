<?php

namespace App\Repository;

use App\Entity\Picture;
use App\Entity\Annonces;
use App\Entity\AnnoncesSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Annonces|null find($id, $lockMode = null, $lockVersion = null)
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
    public function paginateAllVisible(AnnoncesSearch $search, int $page): PaginationInterface
    {
        $query = $this->findVisibleQuery('p');

        if ($search->getCategory()) {
            $query = $query
                ->where('p.category LIKE :cat')
                ->andWhere('p.isValid = :valid')
                ->setParameter('cat', $search->getCategory())
                ->setParameter(':valid', 1)
                ;
        }

        if ($search->getSousCategory()) {
            $query = $query
                ->where('p.sousCategory LIKE :cat')
                ->andWhere('p.isValid = :valid')
                ->setParameter('cat', $search->getSousCategory())
                ->setParameter(':valid', 1)
                ;
        }

        if ($search->getTitle()) {
            $query = $query
                ->where(
                    $query->expr()->andX(
                        $query->expr()->orX(
                            $query->expr()->like('p.title', ':query'),
                            $query->expr()->like('p.smallContent', ':query')
                        )))
                ->andWhere('p.isValid = :valid')        
                ->setParameter('query', '%' . $search->getTitle() . '%')
                ->setParameter(':valid', 1)
                ;
        }  

        $annonces = $this->paginator->paginate(
            $query
            ->where('p.isValid = :valid')
            ->setParameter(':valid', 1)
            ->orderBy('p.createdAt', 'DESC')
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
            ->where('p.isValid = :valid')
            ->setParameter(':valid', 1)
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
            ->where('p.isValid = :valid')
            ->setParameter(':valid', 1)
            ->setMaxResults(6)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
        $this->hydratePicture($annonces);
        return $annonces;
    }

    /**
     * @return Annonces[]
     */
    public function findLatestNonPremium(): array
    {
        $annonces = $this->findVisibleQuery('p')
            ->where('p.isValid = :valid')
            ->andWhere('p.premium = :premium')
            ->setParameter(':valid', 1)
            ->setParameter(':premium', 0)
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
            ->where('p.isValid = :valid')
            ->setParameter(':valid', 1)
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
        ->andWhere('p.isValid = :valid')
        ->setParameter(':valid', 1)
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
            ->andWhere('p.isValid = :valid')
            ->setParameter('cat', $cat)
            ->setParameter(':valid', 1)
            ->setMaxResults(4)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
        $this->hydratePicture($annonces);
        return $annonces;
    }

    /**
     * @return Annonces[]
     */
    public function findCountAll()
    {
        $qb = $this->createQueryBuilder('p');

        return $qb
        ->select('count(p.id)')
        ->where('p.isValid = :valid')
        ->setParameter(':valid', 1)
        ->getQuery()
        ->getSingleScalarResult();

        $this->hydratePicture($qb);

    }

    /**
     * @return Annonces[]
     */
    public function findCountAllPremium()
    {
        $qb = $this->createQueryBuilder('p');

        return $qb
        ->select('count(p.id)')
        ->where('p.isValid = :valid')
        ->andWhere('p.premium = :premium')
        ->setParameter(':valid', 1)
        ->setParameter('premium', 1)
        ->getQuery()
        ->getSingleScalarResult();

        $this->hydratePicture($qb);

    }

    /**
     * @return PaginationInterface
     */
    public function paginateAllVisiblePremium(AnnoncesSearch $search, int $page): PaginationInterface
    {
        $query = $this->findVisibleQuery('p');

        if ($search->getCategory()) {
            $query = $query
                ->where('p.category LIKE :cat')
                ->setParameter('cat', $search->getCategory())
                ->andWhere('p.premium = :premium')
                ->setParameter(':premium', 1)
                ;
        }

        if ($search->getSousCategory()) {
            $query = $query
                ->where('p.sousCategory LIKE :cat')
                ->andWhere('p.premium = :premium')
                ->setParameter('cat', $search->getSousCategory())
                ->setParameter(':premium', 1)
                ;
        }

        if ($search->getTitle()) {
            $query = $query
                ->where(
                    $query->expr()->andX(
                        $query->expr()->orX(
                            $query->expr()->like('p.title', ':query'),
                            $query->expr()->like('p.smallContent', ':query')
                        )))
                ->andWhere('p.premium = :premium')
                ->setParameter('query', '%' . $search->getTitle() . '%')
                ->setParameter(':premium', 1)
                ;
        }  

        $annonces = $this->paginator->paginate(
            $query
            ->where('p.isValid = :valid')
            ->andWhere('p.premium = :premium')
            ->setParameter(':valid', 1)
            ->setParameter('premium', 1)
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
    public function findLatestPremium(): array
    {
        $annonces = $this->findVisibleQuery('p')
            ->where('p.isValid = :valid')
            ->andWhere('p.premium = :premium')
            ->setParameter(':valid', 1)
            ->setParameter(':premium', 1)
            ->setMaxResults(6)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
        $this->hydratePicture($annonces);
        return $annonces;
    }

    /**
     * @return PaginationInterface
     */
    public function paginateAllVisibleUser(string $username, int $page): PaginationInterface
    {
        $query = $this->findVisibleQuery('p');

        $annonces = $this->paginator->paginate(
            $query
            ->where('p.isValid = :valid')
            ->andWhere('p.author = :author')
            ->setParameter(':valid', 1)
            ->setParameter(':author', $username)
            ->orderBy('p.createdAt', 'DESC')
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
    public function paginateAllVisibleCategory(string $category, int $page): PaginationInterface
    {
        $query = $this->findVisibleQuery('p');

        $annonces = $this->paginator->paginate(
            $query
            ->where('p.category = :category')
            ->setParameter('category', $category)
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
    public function findLatestNonPremiumMail(): array
    {
        $annonces = $this->findVisibleQuery('p')
            ->where('p.isValid = :valid')
            ->andWhere('p.premium = :premium')
            ->setParameter(':valid', 1)
            ->setParameter(':premium', 0)
            ->setMaxResults(3)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
        $this->hydratePicture($annonces);
        return $annonces;
    }

    /**
     * @return PaginationInterface
     */
    public function paginateUser(string $username, AnnoncesSearch $search, int $page): PaginationInterface
    {
        $query = $this->findVisibleQuery('p');

        if ($search->getCategory()) {
            $query = $query
                ->where('p.category LIKE :cat')
                ->andWhere('p.isValid = :valid')
                ->andWhere('p.author = :author')
                ->setParameter('cat', $search->getCategory())
                ->setParameter(':author', $username)
                ->setParameter(':valid', 1)
                ;
        }

        if ($search->getSousCategory()) {
            $query = $query
                ->where('p.sousCategory LIKE :cat')
                ->andWhere('p.isValid = :valid')
                ->andWhere('p.author = :author')
                ->setParameter(':author', $username)
                ->setParameter('cat', $search->getSousCategory())
                ->setParameter(':valid', 1)
                ;
        }

        if ($search->getTitle()) {
            $query = $query
                ->where(
                    $query->expr()->andX(
                        $query->expr()->orX(
                            $query->expr()->like('p.title', ':query'),
                            $query->expr()->like('p.smallContent', ':query')
                        )))
                ->andWhere('p.isValid = :valid')  
                ->andWhere('p.author = :author')      
                ->setParameter(':author', $username)
                ->setParameter('query', '%' . $search->getTitle() . '%')
                ->setParameter(':valid', 1)
                ;
        }  

        $annonces = $this->paginator->paginate(
            $query
            ->where('p.isValid = :valid')
            ->andWhere('p.author = :author')
            ->setParameter(':valid', 1)
            ->setParameter(':author', $username)
            ->orderBy('p.createdAt', 'DESC')
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
    public function paginateAllVisibleModerateur(int $page): PaginationInterface
    {
        $query = $this->findVisibleQuery('p');

        $annonces = $this->paginator->paginate(
            $query
            ->where('p.isValid = :valid')
            ->setParameter(':valid', 0)
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
    public function findCountAnnoncesModerateur()
    {
        $qb = $this->createQueryBuilder('p');

        return $qb
        ->select('count(p.id)')
        ->where('p.isValid = :valid')
        ->setParameter(':valid', 0)
        ->getQuery()
        ->getSingleScalarResult();

        $this->hydratePicture($qb);

    }

    /**
     * @return Annonces[]
     */
    public function findCountAnnoncesModerateur2()
    {
        $qb = $this->createQueryBuilder('p');

        return $qb
        ->select('count(p.id)')
        ->where('p.isValid = :valid')
        ->setParameter(':valid', 1)
        ->getQuery()
        ->getSingleScalarResult();

        $this->hydratePicture($qb);

    }

    /**
     * @return PaginationInterface
     */
    public function paginateAllVisibleModerateur2(int $page): PaginationInterface
    {
        $query = $this->findVisibleQuery('p');

        $annonces = $this->paginator->paginate(
            $query
            ->where('p.isValid = :valid')
            ->setParameter(':valid', 1)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery(),
            $page,
            12
        );

        $this->hydratePicture($annonces);

        return $annonces;
    }

}
