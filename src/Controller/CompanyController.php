<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Company;
use Psr\Log\LoggerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * __construct
     *
     * @param  mixed $logger
     * @return void
     */
    public function __construct(ManagerRegistry $doctrine, LoggerInterface $logger)
    {
        $this->doctrine = $doctrine;
        $this->logger = $logger;
        $this->repository = $this->doctrine->getRepository(Company::class);
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
        $form = $this->createFormBuilder()
            ->add('name', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Add'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $name = $data['name'];
            $entityManager = $this->doctrine->getManager();
            $company = new Company();
            $company->setName($name);
            $entityManager->persist($company);
            $entityManager->flush();

            return new Response(
                sprintf(
                    'Id %d and name %s',
                    $company->getId(),
                    $company->getName()
                )
            );
        }

        return $this->render(
            'company/index.html.twig',
            [
                'form' => $form->createView(),
                'company_name' => $this->companyName,
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
    public function updateCompany(int $id): Response
    {
        $updateId = null;
        $entityManager = $this->doctrine->getManager();
        $company = $entityManager->getRepository(Company::class)->find($id);

        if (!empty($company)) {
            $updateId = $company->getId();
            $company->setName('Georgiana12');
            $entityManager->flush();
        }
        return new JsonResponse(
            [
            'update' => !empty($updateId),
            'updateId' => $updateId
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
