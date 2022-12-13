<?php

namespace App\Repository;

use App\Entity\Jobs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Jobs>
 *
 * @method Jobs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jobs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jobs[]    findAll()
 * @method Jobs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jobs::class);
    }

    /**
     * save
     *
     * @param  mixed $entity
     * @return bool
     */
    public function save(Jobs $entity): bool
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        //if previous flush method is succesfull the $entity->getId() will return the id of the inserted company
        //if previous flush method is not succesfull $entity->getId() will return NULL
        return $entity->getId() > 0; // if null willreturn false
    }
          
    /**
     * remove
     *
     * @param  mixed $entity
     * @return void
     */
    public function remove(Jobs $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * do an update on the company table
     *
     * @param  mixed $id     the id of the company record from the database to be updated
     * @param  mixed $params a list of params with the fields and values to update
     * @return int
     */
    public function update(int $id, array $params): int
    {
        //create a new query builder object
        $queryBuilder = $this->createQueryBuilder('j');

        //prepare an update statement
        $nbUpdatedRows = $queryBuilder->update()
            ->set('j.name', ':jobName')
            ->set('j.description', ':jobDescription')
            ->set('j.active', ':jobActive')
            ->set('j.priority', ':jobPriority')
            ->where('j.id = :jobId')
            ->setParameter('jobName', $params['name'])
            ->setParameter('jobDescription', $params['description'])
            ->setParameter('jobActive', $params['active'])
            ->setParameter('jobPriority', $params['priority'])
            ->setParameter('jobId', $id)
            ->getQuery()
            ->execute();

        return $nbUpdatedRows;
    }


//    /**
//     * @return Jobs[] Returns an array of Jobs objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('j.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Jobs
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
