<?php

namespace App\Repository;

use App\Entity\Picture;
use App\Entity\Annonces;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Picture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Picture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Picture[]    findAll()
 * @method Picture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Picture|null findForProperties(array $properties)
 */
class PictureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Picture::class);
    }

    /**
     * @param Annonces[] $annonces
     * @return ArrayCollection
     */
    public function findForAnnonces(array $annonces): ArrayCollection
    {
        $qb = $this->createQueryBuilder('p');
        $pictures = $qb
            ->select('p')
            ->where(
                $qb->expr()->in(
                    'p.id',
                    $this->createQueryBuilder('p2')
                        ->select('MIN(p2.id)')
                        ->where('p2.annonces IN (:annonces)')
                        ->groupBy('p2.annonces')
                        ->getDQL()
                )
            )
            ->getQuery()
            ->setParameter('annonces', $annonces)
            ->getResult();
        $pictures = array_reduce($pictures, function (array $acc, Picture $picture) {
            $acc[$picture->getAnnonces()->getId()] = $picture;
            return $acc;
        }, []);
        return new ArrayCollection($pictures);
    }

}
