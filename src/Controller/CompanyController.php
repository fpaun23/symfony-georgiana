<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Company;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CompanyRepository;

/**
 * CompanyController
 */
class CompanyController extends AbstractController
{
    /**
     * logger
     *
     * @var mixed
     */
    private $logger;

    /**
     * companyName
     *
     * @var string
     */
    public $companyName = 'Devnest';

    /**
     * @var ManagerRegistry
     */
    private ManagerRegistry $doctrine;

    private mixed $repository;

    /**
     * @var CompanyRepository
     */
    private CompanyRepository $companyRepository;

    /**
     * __construct
     *
     * @param  mixed $logger
     * @return void
     */
    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }


    /**
     * addCompany
     *
     * @param  mixed $request
     * @return Response
     */
    public function addCompany(Request $request): Response
    {
        //get all request parameters : $request->query->all()

        //get request parameter with key 'name
        $name = $request->get('name');

        //create new Company objec
        $newCompany = new Company();
        $newCompany->setName($name);

        //call the Repository save function with flush set to true
        $this->companyRepository->save($newCompany, true);

        return new JsonResponse(
            [
             'saved' => true,
             'name' => $name
            ]
        );
    }

    /**
     * updateCompany
     *
     * @param  mixed $doctrine
     * @param  mixed $id
     * @return Response
     */
    public function updateCompany(Request $request, int $id): Response
    {
        $requestParams = $request->query->all();
        $updateResult = $this->companyRepository->update($id, $requestParams);

        return new JsonResponse(
            [
                'rows_updated' => $updateResult
            ]
        );
    }

    /**
     * deleteCompany
     *
     * @param  mixed $doctrine
     * @param  mixed $id
     * @return Response
     */
    public function deleteCompany(int $id): Response
    {
        $deletedId = null;
        $entityManager = $this->doctrine->getManager();
        $company = $entityManager->getRepository(Company::class)->find($id);

        if (!empty($company)) {
            $deletedId = $company->getId();
            $entityManager->remove($company);
            $entityManager->flush();
        }

        return new JsonResponse(
            [
            'detleted' => !empty($deletedId),
            'deleteId' => $deletedId
            ]
        );
    }

    /**
     * listCompany
     *
     * @return Response
     */
    public function listCompany(): Response
    {
        $entityManager = $this->doctrine->getManager();
        $queryBuilder = $entityManager->createQueryBuilder();
        $query = $queryBuilder
            ->select('c')
            ->from(Company::class, 'c')
            ->getQuery();

        $sql = $query->getArrayResult();
        return new JsonResponse($sql);
    }
    
    /**
     * companyId
     *
     * @param  mixed $id
     * @return Response
     */
    public function companyId(int $id): Response
    {
        $entityManager = $this->doctrine->getManager();
        $queryBuilder = $entityManager->createQueryBuilder();
        $query = $queryBuilder
            ->select('c.name')
            ->from(Company::class, 'c')
            ->where('c.id = :identifier')
            ->setParameter('identifier', $id)
            ->getQuery();
        $sql = $query->getResult();
        return new JsonResponse($sql);
    }
    
    /**
     * companyName
     *
     * @param  mixed $name
     * @return Response
     */
    public function companyName(string $name): Response
    {
        $entityManager = $this->doctrine->getManager();
        $queryBuilder = $entityManager->createQueryBuilder();
        $query = $queryBuilder
            ->select('c.id, c.name')
            ->from(Company::class, 'c')
            ->where('c.name = :identifier')
            ->setParameter('identifier', $name)
            ->getQuery();
        $sql = $query->getResult();
        return new JsonResponse($sql);
    }
        
    /**
     * likeCompanyName
     *
     * @param  mixed $name
     * @return Response
     */
    public function likeCompanyName(string $name): Response
    {
        $entityManager = $this->doctrine->getManager();
        $queryBuilder = $entityManager->createQueryBuilder();
        $query = $queryBuilder
            ->select('c.id, c.name')
            ->from(Company::class, 'c')
            ->where('c.name LIKE :identifier')
            ->setParameter('identifier', '%' . $name . '%')
            ->getQuery();
        $sql = $query->getResult();
        return new JsonResponse($sql);
    }
}
