<?php

declare(strict_types=1);

namespace App\Validator;

class BulkValidator
{
    public function isValid(array $jobs): bool
    {
        if (empty($jobs[0])) {
            throw new \InvalidArgumentException('Name is not set or missing key name');
        }

        if (strlen($jobs[0]) <= 2) {
            throw new \InvalidArgumentException('Name length cannot be less than 2');
        }

        if (empty($jobs[1])) {
            throw new \InvalidArgumentException('Description is not set or missing key description');
        }

        if (strlen($jobs[1]) <= 5) {
            throw new \InvalidArgumentException('Description length cannot be less than 5');
        }

        if ($jobs[2] != 1 && $jobs[2] != 0) {
            throw new \InvalidArgumentException('Active must be 1 or 0!');
        }
        
        if ($jobs[3] != 1 && $jobs[3] != 0) {
            throw new \InvalidArgumentException('Priority must be 1 or 0!');
        }

        return true;
    }

}