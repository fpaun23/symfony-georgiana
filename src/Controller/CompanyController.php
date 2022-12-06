<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Company;
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
     * @var CompanyRepository
     */
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
}
