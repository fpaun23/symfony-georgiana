<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ContactController extends AbstractController
{
    public function displayContact(): Response
    {
        $name = 'Georgiana Panaete';

        return $this->render('contact/index.html.twig', [
            'name' => $name,
        ]);
    }
}
