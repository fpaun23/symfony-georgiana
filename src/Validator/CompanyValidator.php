<?php

declare(strict_types=1);

namespace App\Validator;

/**
 * CompanyValidator
 */
class CompanyValidator
{
    /**
     * nameValidator
     *
     * @param  mixed $data
     * @return bool
     */
    public function nameValidatorArray(array $data): bool
    {
        if (empty($data['name'])) {
            throw new \InvalidArgumentException('Company name is not set or missing key name');
        }

        if (strlen($data['name']) <= 2) {
            throw new \InvalidArgumentException('Company name length cannot be less than 2');
        }

        return true;
    }


    /**
     * nameValidator
     *
     * @param  mixed $name
     * @return bool
     */
    public function nameValidator(string $name): bool
    {
        if (strlen($name) <= 2) {
            throw new \InvalidArgumentException('Company name length cannot be less than 2');
        }

        return true;
    }

    /**
     * idValidator
     *
     * @param  mixed $id
     * @return bool
     */
    public function idValidator(int $id): bool
    {
        if ($id > 0) {
            throw new \InvalidArgumentException('Company id length cannot be less than 0');
        }

        return true;
    }
}
