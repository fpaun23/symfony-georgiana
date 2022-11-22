<?php
namespace App;

use App\DataValidatorInterface;

class DataValidatorContact implements DataValidatorInterface
{
    public function validate(array $args) : bool
    {
        $errors = [];
        if ((strlen($args[0]) < Constants::RANGE[0] || strlen($args[0]) > Constants::RANGE[1]) || !preg_match("/^[a-zA-Z]+$/", $args[0])) {
            $errors[] = "name";
        }

        if (!filter_var($args[1], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "email";
        }

        if (strlen($args[2]) < Constants::RANGE[2] || strlen($args[2]) > Constants::RANGE[3]) {
            $errors[] = "description";
        }

        if (empty($errors)) {
            return true;
        } else {
            return false;
        }
    }
}