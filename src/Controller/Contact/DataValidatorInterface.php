<?php
namespace App\Contact;

interface DataValidatorInterface
{
    public function validate(array $args) : bool;
}