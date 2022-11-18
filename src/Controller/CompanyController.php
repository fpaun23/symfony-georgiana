<?php

declare (strict_types = 1);

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyController extends AbstractController
{
    private $logger;
    public $companyName = 'Devnest';


    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function displayCompany(): Response
    {
        return $this->render(
            'company/index.html.twig', [
            'company_name' => $this->companyName,
            ]
        );
    }

    public function addCompany(Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Add'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $name = $data['name'];
            $description = $data['description'];

            $this->logger->notice(
                "Submission Successful",
                [json_encode(['name' => $name, 'description' => $description])]
            );

            return $this->redirectToRoute('app_company');
        }

        return $this->render(
            'company/index.html.twig', [
            'form' => $form->createView(),
            'company_name' => $this->companyName,
            ]
        );

    }
}
