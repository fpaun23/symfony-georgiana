<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    public function displayHome(): Response
    {
        $contact = 'Contact';
        $company = "Company";

        return $this->render('home/index.html.twig', [
            'contact' => $contact,
            'company' => $company,
        ]);
    }
}
