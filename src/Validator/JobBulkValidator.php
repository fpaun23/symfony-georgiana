<?php

declare(strict_types=1);

namespace App\Validator;

class JobBulkValidator
{
    public const MANDATORY_JOB_ARRAY_KEYS = ['name', 'description', 'priority', 'active', 'company_id'];
    
    /**
     * isValid
     *
     * @param  mixed $jobs
     * @return bool
     */
    public function isValid(array $jobs): bool
    {
        if (empty($jobs)) {
            return false;
        }

        foreach (self::MANDATORY_JOB_ARRAY_KEYS as $key) {
            if (!array_key_exists($key, $jobs)) {
                return false;
            }
        }
        return true;
    }
}
