<?php
namespace App;

interface DataValidatorInterface
{
    public function validate(array $args) : bool;
}