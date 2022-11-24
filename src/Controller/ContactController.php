<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Service\DataValidatorInterface;

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
     * nameContact
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
     * email
     *
     * @var string
     */
    public $email = '';

    /**
     * description
     *
     * @var string
     */
    public $description = '';


    /**
     * errorMessage
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
     * @param  mixed $validator
     * @return void
     */
    public function __construct(LoggerInterface $logger, DataValidatorInterface $validator)
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
        $isValid = $this->validator->isValid([$name, $email, $description]);

        if ($isValid) {
            $this->logger->notice(
                "Submission Successful",
                [json_encode(['name' => $name, 'description' => $description, 'email' => $email])]
            );
            $this->redirectToRoute("app_contact");
        } else {
            $this->errorMessage = $this->validator->getError();
            $this->redirectToRoute("app_contact");
        }
        return $this->displayContact();
    }
}
