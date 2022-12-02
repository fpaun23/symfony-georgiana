<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Company;
use Psr\Log\LoggerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

            // $this->logger->notice(
            //     "Submission Successful",
            //     [json_encode(['name' => $name])]
            // );

            // return $this->redirectToRoute('app_company');

            return new Response(sprintf(
                'Id %d and name %s',
                $company->getId(),
                $company->getName()
            ));
        }

        return $this->render(
            'company/index.html.twig',
            [
                'form' => $form->createView(),
                'company_name' => $this->companyName,
            ]
        );
    }

    public function list(): Response
    {
        $companies = $this->repository->findAll();
        return $this->json($companies);
    }
}
