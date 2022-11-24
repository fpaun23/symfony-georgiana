<?php

namespace App\Service;

interface DataValidatorInterface
{
    public function isValid(array $args): bool;
    public function getError(): string;
}
