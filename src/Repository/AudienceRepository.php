<?php

namespace App\Repository;

use App\Entity\Audience;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Audience>
 *
 * @method Audience|null find($id, $lockMode = null, $lockVersion = null)
 * @method Audience|null findOneBy(array $criteria, array $orderBy = null)
 * @method Audience[]    findAll()
 * @method Audience[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AudienceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Audience::class);
    }

    public function add(Audience $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Audience $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    /**
     * @return Audience[] Returns an array of Audience objects
     */
    public function derniereAudience(): array
    {
        return $this->createQueryBuilder('a')
            ->Join('a.contentieux', 'c')
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Audience[] Returns an array of Audience objects
     */
    public function toDayAudience(): array
    {
        return $this->createQueryBuilder('a')
            ->Where('a.createdAt LIKE :today')
            ->setParameter('today', '%' . Date('Y-m-d') . '%')
            ->orderBy('a.createdAt', 'ASC')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Audience[] Returns an array of Audience objects
     */
    public function jours7Audience(): array
    {
        return $this->createQueryBuilder('a')
            ->Where('a.createdAt BETWEEN :now AND :today')
            ->setParameter('now', Date('Y/m/d'))
            ->setParameter('today', Date('Y/m/d', strtotime('+7 day')))
            ->orderBy('a.createdAt', 'ASC')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Audience[] Returns an array of Audience objects
     */
    public function searchAudience($search): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.createdAt LIKE :today')
            ->setParameter('today', '%' . $search . '%')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Audience[] Returns an array of Audience objects
     */
    public function clientAudience($client): array
    {
        return $this->createQueryBuilder('a')
            ->Join('a.contentieux', 'c')
            ->Where('c.client = :client')
            ->setParameter('client', $client)
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Audience[] Returns an array of Audience objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Audience
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
