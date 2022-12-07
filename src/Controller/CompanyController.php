<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * CompanyController
 */
class CompanyController extends AbstractController
{
    private CompanyRepository $companyRepository;

    /**
     * __construct
     *
     * @param  mixed $companyRepository
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
     * @param  mixed $request
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
     * @param  mixed $id
     * @return Response
     */
    public function deleteCompany(int $id): Response
    {
        $companies = $this->companyRepository->findAll();
        foreach ($companies as $company) {
            if ($company->getId() === $id) {
                $this->companyRepository->remove($company);
                return new JsonResponse(
                    [
                        'row_deleted' => $id
                    ]
                );
            } else {
                return new JsonResponse(
                    [
                        'rows_deleted' => 0
                    ]
                );
            }
        }
    }

    /**
     * listCompany
     *
     * @return Response
     */
    public function listCompany(): Response
    {
        $result = $this->companyRepository->getAllCompanies();

        return new JsonResponse(
            [
                'rows' => $result
            ]
        );
    }

    /**
     * companyId
     *
     * @param  mixed $id
     * @return Response
     */
    public function companyId(int $id): Response
    {
        $result = $this->companyRepository->getCompanyId($id);

        return new JsonResponse(
            [
                'rows' => $result
            ]
        );
    }

    /**
     * companyName
     *
     * @param  mixed $name
     * @return Response
     */
    public function companyName(string $name): Response
    {
        $result = $this->companyRepository->getCompanyName($name);

        return new JsonResponse($result);
    }


    /**
     * likeCompanyName
     *
     * @param  mixed $name
     * @return Response
     */
    public function likeCompanyName(string $name): Response
    {
        $result = $this->companyRepository->getLikeCompanyName($name);

        return new JsonResponse($result);
    }

    // /**
    //  * likeCompanyName
    //  *
    //  * @param  mixed $name
    //  * @return Response
    //  */
    // public function likeCompanyName(string $name): Response
    // {
    //     $likeCompanyName = $this->companyRepository->findOneBy(['name' => $name]);
    //     $result = $this->companyRepository->getLikeCompanyName($likeCompanyName);
    //     return new JsonResponse(
    //         [
    //             'rows' => $result
    //         ]
    //     );
    // }
}
