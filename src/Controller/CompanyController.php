<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * CompanyController
 */
class CompanyController extends AbstractController
{
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

    private CompanyRepository $companyRepository;

    /**
     * __construct
     *
     * @param  mixed $logger
     * @return void
     */
    public function __construct(ManagerRegistry $doctrine, CompanyRepository $companyRepository)
    {
        $this->doctrine = $doctrine;
        $this->companyRepository = $companyRepository;
    }

    /**
     * displayCompany
     *
     * @return Response
     */
    public function displayCompany(): Response
    {
        return $this->render(
            'company/index.html.twig',
            [
                'company_name' => $this->companyName,
            ]
        );
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
        $companySaved = $this->companyRepository->save($newCompany);

        return new JsonResponse(
            [
                'saved' => $companySaved,
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
        $companyToDelete = $this->companyRepository->find($id);
        $companyDeleted = $this->companyRepository->remove($companyToDelete);

        return new JsonResponse(
            [
                'rows_deleted' => $companyDeleted
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
            ->getQuery()
            ->getArrayResult();

        return new JsonResponse($query);
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
            ->getQuery()
            ->getResult();

        return new JsonResponse($query);
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
            ->getQuery()
            ->getResult();

        return new JsonResponse($query);
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
            ->getQuery()
            ->getResult();
        return new JsonResponse($query);
    }
}
