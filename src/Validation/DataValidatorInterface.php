<?php

namespace App\Validation;

interface DataValidatorInterface
{
    public function isValid(array $args): bool;
    public function getError(): string;
}
