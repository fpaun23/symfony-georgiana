<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Constants;

/**
 * ContactController
 */
class ContactController extends AbstractController
{
    /**
     * name
     *
     * @var string
     */
    public $name = 'Georgiana Panaete';

    public function displayContact(): Response
    {
        return $this->render(
            'contact/index.html.twig', [
            'name' => $this->name,
            ]
        );
    }
    
    /**
     * addContact
     *
     * @return Response
     */
    public function addContact(): Response
    {
        $errors = [];
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST["name"];
            $email = $_POST["email"];
            $description = $_POST["description"];
        
            if ((strlen($name) < Constants::RANGE[0] || strlen($name) > Constants::RANGE[1]) || !preg_match("/^[a-zA-Z]+$/", $name)) {
                $errors[] = "name";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "email";
            }

            if (strlen($description) < Constants::RANGE[2] || strlen($description) > Constants::RANGE[3]) {
                $errors[] = "description";
            }
        }
            return $this->render(
                'contact/add/index.html.twig', [
                'errors' => $errors,
                ]
            );
    }
}