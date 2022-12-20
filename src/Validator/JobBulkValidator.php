<?php

declare(strict_types=1);

namespace App\Validator;

class JobBulkValidator
{
    public const MANDATORY_JOB_ARRAY_KEYS = ['name', 'description', 'priority', 'active', 'company'];
        
    /**
     * isValid
     *
     * @param  mixed $keys
     * @return bool
     */
    public function isValid(array $keys): bool
    {
        if (!empty(array_diff(self::MANDATORY_JOB_ARRAY_KEYS, $keys))) {
            return false;
        }
        return true;
    }
}
