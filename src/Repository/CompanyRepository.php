<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Company>
 *
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneBy(array $criteria, array $orderBy = null)
 * @method Company[]    findAll()
 * @method Company[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }
    
    /**
     * save a new company
     *
     * @param  mixed $entity an object of thpe EntityCompany
     * @return bool
     */
    public function save(Company $entity): bool
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        //if previous flush method is succesfull the $entity->getId() will return the id of the inserted company
        //if previous flush method is not succesfull $entity->getId() will return NULL
        return $entity->getId() > 0; // if null willreturn false
    }

    public function remove(Company $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * do an update on the company table
     *
     * @param  mixed $id the id of the company record from the database to be updated
     * @param  mixed $params a list of params with the fields and values to update
     * @return int
     */
    public function update(int $id, array $params): int
    {
        //create a new query builder object
        $queryBuilder = $this->createQueryBuilder('c');

        //prepare an update statement
        $nbUpdatedRows = $queryBuilder->update()
            ->set('c.name', ':companyName')
            ->where('c.id = :companyId')
            ->setParameter('companyName', $params['name'])
            ->setParameter('companyId', $id)
            ->getQuery()
            ->execute();
            //->execute() will return the number of affected rows by the sql query (in this case updated rows)
            //the result is 0 if no updates and >= 1 if updates done

            return $nbUpdatedRows;
    }

//    /**
//     * @return Company[] Returns an array of Company objects
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

//    public function findOneBySomeField($value): ?Company
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
