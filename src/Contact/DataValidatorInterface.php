<?php

namespace App\Contact;

interface DataValidatorInterface
{
    public function isValid(array $args): bool;
}
