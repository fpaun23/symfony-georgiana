<?php

namespace App\Service;

use App\Service\DataValidatorInterface;
use App\Validation\Constants;

/**
 * DataValidatorContact
 */
class DataValidatorContact2 implements DataValidatorInterface
{
    /**
     * isValid
     *
     * @param  mixed $args
     * @return bool
     */
    public function isValid(array $args): bool
    {
        $errors = [];
        if ((strlen($args[0]) < Constants::RANGE[0] || strlen($args[0]) > Constants::RANGE[1]) ||
            !preg_match("/^[a-zA-Z]+$/", $args[0])
        ) {
            $errors[] = "name";
        }

        if (!filter_var($args[1], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "email";
        }

        if (strlen($args[2]) < Constants::RANGE[2] || strlen($args[2]) > Constants::RANGE[3]) {
            $errors[] = "description";
        }

        if (empty($errors)) {
            return false;
        } else {
            return true;
        }
    }

    public function getError(): string
    {
        return Constants::ERROR_MESSAGE;
    }
}
