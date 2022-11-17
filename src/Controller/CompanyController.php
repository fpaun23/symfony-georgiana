<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class CompanyController extends AbstractController
{
    public function displayCompany(): Response
    {
        $companyName = 'Devnest';

        return $this->render('company/index.html.twig', [
            'company_name' => $companyName,
        ]);
    }
}
