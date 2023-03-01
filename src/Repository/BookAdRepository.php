<?php

namespace App\Repository;

use App\Entity\BookAd;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BookAd>
 *
 * @method BookAd|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookAd|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookAd[]    findAll()
 * @method BookAd[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookAdRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookAd::class);
    }

    public function save(BookAd $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BookAd $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
        * @return BookAd[] Returns an array of BookAd objects
        */
    public function findByUser($userId): array
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.user', 'user')
            ->andWhere('user.id = :val')
            ->setParameter('val', $userId)
            ->orderBy('b.updatedAt', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    // function to find book ads in the distance $dist from point $lat & $lng

    public function findByDist($lat, $lng, $dist): array
    {
        $sql = 'SELECT * FROM book_ad b JOIN user u ON user.id = b.user_id WHERE (u.longitude - ' . $lng . ')*111*cos(' . $lat .') * (u.longitude - ' . $lng . ')*111*cos(' . $lat .') + (u.latitude-' . $lat .')*111 * (u.latitude-' . $lat .')*111 < ' . $dist * $dist;
        
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery([]);

        return $result->fetchAllAssociative();
    }

//    public function findOneBySomeField($value): ?BookAd
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
