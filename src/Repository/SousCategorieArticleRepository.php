<?php

namespace App\Repository;

use App\Entity\SousCategorieArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SousCategorieArticle>
 *
 * @method SousCategorieArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method SousCategorieArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method SousCategorieArticle[]    findAll()
 * @method SousCategorieArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SousCategorieArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SousCategorieArticle::class);
    }

    public function save(SousCategorieArticle $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SousCategorieArticle $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SousCategorieArticle[] Returns an array of SousCategorieArticle objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SousCategorieArticle
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
