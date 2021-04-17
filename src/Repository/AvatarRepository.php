<?php

namespace App\Repository;

use App\Entity\Avatar;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Avatar|null find($id, $lockMode = null, $lockVersion = null)
 * @method Avatar|null findOneBy(array $criteria, array $orderBy = null)
 * @method Avatar[]    findAll()
 * @method Avatar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Avatar|null findForProperties(array $properties)
 */
class AvatarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avatar::class);
    }

    /**
     * @param User[] $users
     * @return ArrayCollection
     */
    public function findForUser(array $users): ArrayCollection
    {
        $qb = $this->createQueryBuilder('p');
        $pictures = $qb
            ->select('p')
            ->where(
                $qb->expr()->in(
                    'p.id',
                    $this->createQueryBuilder('p2')
                        ->select('MIN(p2.id)')
                        ->where('p2.user IN (:users)')
                        ->groupBy('p2.user')
                        ->getDQL()
                )
            )
            ->getQuery()
            ->setParameter('users', $users)
            ->getResult();
        $pictures = array_reduce($pictures, function (array $acc, Avatar $picture) {
            $acc[$picture->getUser()->getId()] = $picture;
            return $acc;
        }, []);
        return new ArrayCollection($pictures);
    }

}
