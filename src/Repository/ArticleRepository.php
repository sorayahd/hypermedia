<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function save(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function ArticleEnPromotion($value): array
       {
           return $this->createQueryBuilder('a')
               ->andWhere('a.promotion > :val')
               ->setParameter('val', $value)
               ->orderBy('a.id', 'ASC')
               ->getQuery()
               ->getResult()
           ;
       }



       public function findArray($array)
       {
           $qb = $this->createQueryBuilder('u')
                   ->select('u')
                   ->where('u.id IN (:array)')
                   ->setParameter('array', $array);  
           return $qb->getQuery()->getResult();      
       }
       

       public function findSame($categorie,$id)
       {
      
        return $this->createQueryBuilder('a')
        ->andWhere('a.categorie = :categorie')
        ->setParameter('categorie', $categorie)
        ->andWhere('a.id NOT IN (:id)')
       
        ->setParameter('id',$id)
        
        //->orderBy('rand()')
        // ->setMaxResults(5)
        ->getQuery()

        ->getResult()
    ;
       }
       
       










       public function findOneRandomBy($criteria = [])
       {
           $qb = $this->createQueryBuilder('entity')
               ->select('MIN(entity.id)', 'MAX(entity.id)')
           ;
   
           foreach ($criteria as $field => $value) {
               $qb
                   ->andWhere(sprintf('entity.%s=:%s', $field, $field))
                   ->setParameter(':'.$field, $value);
               ;
           }
   
           $id_limits = $qb
               ->getQuery()
               ->getOneOrNullResult();
           $random_possible_id = rand($id_limits[1], $id_limits[2]);
   
           return $qb
               ->select('entity')
               ->andWhere('entity.id >= :random_id')
               ->setParameter('random_id', $random_possible_id)
               ->setMaxResults(1)
               ->getQuery()
               ->getOneOrNullResult()
               ;
       }










       
       public function findArticle($motcle)
       {
           $query = $this->createQueryBuilder('f')
               ->where('f.nom like :nom ')
               ->setParameter('nom', '%' . $motcle . '%')
               ->orderBy('f.nom', 'ASC')
               ->getQuery();
   
           return $query->getResult();
       }




   


//    /**
//     * @return Article[] Returns an array of Article objects
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

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }




}
