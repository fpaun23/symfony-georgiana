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
     * update
     *
     * @param  mixed $id
     * @param  mixed $params
     * @return int
     */
    public function update(int $id, array $params): int
    {
        //create a new query builder object
        $queryBuilder = $this->createQueryBuilder('j');

        //prepare an update statement
        $queryBuilder->update()
            ->where('j.id = :jobId')
            ->setParameter('jobId', $id);
        if (!empty($params['name'])) {
            $queryBuilder->set('j.name', ':jobName');
            $queryBuilder->setParameter('jobName', $params['name']);
        }

        if (!empty($params['description'])) {
            $queryBuilder->set('j.description', ':jobDescription');
            $queryBuilder->setParameter('jobDescription', $params['description']);
        }

        if (!empty($params['active'])) {
            $queryBuilder->set('j.active', ':jobActive');
            $queryBuilder->setParameter('jobActive', $params['active']);
        }

        if (!empty($params['priority'])) {
            $queryBuilder->set('j.priority', ':jobPriority');
            $queryBuilder->setParameter('jobPriority', $params['priority']);
        }

        $nbUpdatedRows = $queryBuilder->getQuery()->execute();

        return $nbUpdatedRows;
    }

    /**
     * getJobByName
     *
     * @param  mixed $name
     * @return array
     */
    public function getJobByName(string $name): array
    {
        $queryBuilder = $this->createQueryBuilder('j');
        $company = $queryBuilder
            ->where('j.name = :searchTerm')
            ->setParameter('searchTerm', $name)
            ->getQuery()
            ->getResult();

        return $company;
    }

    /**
     * getLikeJobName
     *
     * @param  mixed $name
     * @return array
     */
    public function getLikeJobName(string $name): array
    {
        $queryBuilder = $this->createQueryBuilder('j');
        $company = $queryBuilder
            ->where('j.name LIKE :identifier')
            ->setParameter('identifier', '%' . $name . '%')
            ->getQuery()
            ->getResult();

        return $company;
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
