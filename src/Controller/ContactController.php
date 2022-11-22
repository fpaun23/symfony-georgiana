<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Constants;
use App\DataValidatorInterface;
use App\DataValidatorContact;

/**
 * ContactController
 */
class ContactController extends AbstractController
{
    /**
     * logger
     *
     * @var mixed
     */
    private $logger;

    /**
     * name
     *
     * @var string
     */
    public $nameContact = 'Georgiana Panaete';

    /**
     * name
     *
     * @var string
     */
    public $name = '';

    /**
     * name
     *
     * @var string
     */
    public $email = '';

    /**
     * name
     *
     * @var string
     */
    public $description = '';
    
    /**
     * message
     *
     * @var string
     */
    public $errorMessage = '';
        
    /**
     * validator
     *
     * @var mixed
     */
    public $validator;

    /**
     * __construct
     *
     * @param  mixed $logger
     * @return void
     */
    public function __construct(LoggerInterface $logger, DataValidatorContact $validator)
    {
        $this->logger = $logger;
        $this->validator = $validator;
    }

    /**
     * displayContact
     *
     * @return Response
     */
    public function displayContact(): Response
    {
        return $this->render(
            'contact/index.html.twig',
            [
                'name_contact' => $this->nameContact,
                'error_message' => $this->errorMessage,
                'name' => $this->name,
                'email' => $this->email,
                'description' => $this->description,
            ]
        );
    }

    /**
     * addContact
     *
     * @return Response
     */
    public function addContact(Request $request): Response
    {
        $name = $request->request->get('name');
        $email =  $request->request->get('email');
        $description = $request->request->get('description');
        $isValidated = $this->validator->validate([$name,$email,$description]); 
       
        if ($isValidated) {
            $this->logger->notice(
                "Submission Successful",
                [json_encode(['name' => $name, 'description' => $description, 'email' => $email])]
            );
        } else {
            $this->errorMessage = "Form sumbmission failed!";
        }

        return $this->displayContact();
    }
}
