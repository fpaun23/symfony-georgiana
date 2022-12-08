<?php

declare(strict_types=1);

namespace App\Controller;

use Exception;
use App\Entity\Company;
use App\Validator\CompanyValidator;
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

    private CompanyValidator $companyValidator;

    /**
     * __construct
     *
     * @param  mixed $companyRepository
     * @return void
     */
    public function __construct(CompanyRepository $companyRepository, CompanyValidator $companyValidator)
    {
        $this->companyRepository = $companyRepository;
        $this->companyValidator = $companyValidator;
    }

    /**
     * addCompany
     *
     * @param  mixed $request
     * @return Response
     */
    public function addCompany(Request $request): Response
    {
        try {
            //get all request parameters : $request->query->all()

            //get request parameter with key 'name
            $name = $request->get("name");

            $companies = $request->query->all();

            $this->companyValidator->nameValidatorArray($companies);

            //create new Company objec
            $newCompany = new Company();
            $newCompany->setName($name);

            //call the Repository save function with flush set to true
            $companySaved = $this->companyRepository->save($newCompany);

            return new JsonResponse(
                [
                    'results' => [
                        'added' => $companySaved,
                        'error' => false,
                    ]
                ]
            );
        } catch (Exception $e) {
            return new JsonResponse(
                [
                    'results' => [
                        'error' => true,
                        'message' => $e->getMessage()
                    ]
                ]
            );
        }
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
        try {
            $requestParams = $request->query->all();
            $this->companyValidator->nameValidatorArray($requestParams);

            $updateResult = $this->companyRepository->update($id, $requestParams);

            return new JsonResponse(
                [
                    'results' => [
                        'updated' => $updateResult,
                        'error' => false,
                    ]
                ]
            );
        } catch (Exception $e) {
            return new JsonResponse(
                [
                    'results' => [
                        'error' => true,
                        'message' => $e->getMessage()
                    ]
                ]
            );
        }
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
            if (($company->getId() === $id) && isset($id)) {
                try {
                    $this->companyValidator->idValidator($id);
                    $this->companyRepository->remove($company);
                    return new JsonResponse(
                        [
                            'results' => [
                                'deleted' => $id,
                                'error' => false,
                            ]
                        ]
                    );
                } catch (Exception $e) {
                    return new JsonResponse(
                        [
                            'results' => [
                                'error' => true,
                                'message' => $e->getMessage()
                            ]
                        ]
                    );
                }
            } else {
                return new JsonResponse(
                    [
                        'results' => [
                            'deleted' => 0,
                            'error' => false,
                        ]
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
        try {
            $this->companyValidator->idValidator($id);

            $result = $this->companyRepository->getCompanyId($id);

            return new JsonResponse(
                [
                    'results' => [
                        'companies' => $result,
                        'error' => false,
                    ]
                ]
            );
        } catch (Exception $e) {
            return new JsonResponse(
                [
                    'results' => [
                        'error' => true,
                        'message' => $e->getMessage()
                    ]
                ]
            );
        }
    }

    /**
     * companyName
     *
     * @param  mixed $name
     * @return Response
     */
    public function companyName(string $name): Response
    {
        try {
            $this->companyValidator->nameValidator($name);

            $result = $this->companyRepository->getCompanyName($name);

            return new JsonResponse(
                [
                    'results' => [
                        'companies' => $result,
                        'error' => false,
                    ]
                ]
            );
        } catch (Exception $e) {
            return new JsonResponse(
                [
                    'results' => [
                        'error' => true,
                        'message' => $e->getMessage()
                    ]
                ]
            );
        }
    }

    /**
     * likeCompanyName
     *
     * @param  mixed $name
     * @return Response
     */
    public function likeCompanyName(string $name): Response
    {
        try {
            $this->companyValidator->nameValidator($name);

            $result = $this->companyRepository->getlikeCompanyName($name);

            return new JsonResponse(
                [
                    'results' => [
                        'companies' => $result,
                        'error' => false,
                    ]
                ]
            );
        } catch (Exception $e) {
            return new JsonResponse(
                [
                    'results' => [
                        'error' => true,
                        'message' => $e->getMessage()
                    ]
                ]
            );
        }
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
