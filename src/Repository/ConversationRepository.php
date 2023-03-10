<?php

namespace App\Repository;

use App\Entity\Conversation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\ChoiceList\ORMQueryBuilderLoader;

/**
 * @extends ServiceEntityRepository<Conversation>
 *
 * @method Conversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversation[]    findAll()
 * @method Conversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    public function save(Conversation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Conversation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getUserConversationQueryBuilder(int $userId): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.user', 'user')
            ->andWhere('user.id = :userId')
            ->setParameter('userId', $userId);
    }

    public function getMessageByConversationQueryBuilder(int $conversationId): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.id = :conversationId')
            ->setParameter('conversationId', $conversationId);
    }

    public function getConversationByUserIdAndBookAd(int $userId, int $bookAdId): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.user', 'user')
            ->andWhere('user.id = :userId')
            ->setParameter('userId', $userId)
            ->leftJoin('c.bookAd', 'bookAd')
            ->andWhere('bookAd.id = :bookAdId')
            ->setParameter('bookAdId', $bookAdId)
            ->getQuery()
            ->getResult();
    }
    

    

//    /**
//     * @return Conversation[] Returns an array of Conversation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Conversation
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
