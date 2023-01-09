<?php

namespace App\Repository;

use App\Entity\ArticleSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ArticleSearch>
 *
 * @method ArticleSearch|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleSearch|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleSearch[]    findAll()
 * @method ArticleSearch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleSearchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleSearch::class);
    }

    public function save(ArticleSearch $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ArticleSearch $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


   

//    /**
//     * @return ArticleSearch[] Returns an array of ArticleSearch objects
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

//    public function findOneBySomeField($value): ?ArticleSearch
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
