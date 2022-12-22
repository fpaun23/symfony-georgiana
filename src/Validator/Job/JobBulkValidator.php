<?php

declare(strict_types=1);

namespace App\Validator\Job;

use App\Entity\Company;

class JobBulkValidator
{
    public const MANDATORY_JOB_ARRAY_KEYS = ['name', 'description', 'priority', 'active', 'company_id'];

    /**
     * isValid
     *
     * @param  mixed $keys
     * @return bool
     */
    public function isValid(array $job): bool
    {
        foreach (self::MANDATORY_JOB_ARRAY_KEYS as $key) {

            if (!in_array($key, $job)) {
                return false;
            }
        }

        return true;
    }

    public function companyIsValid(?Company $company): bool
    {
        return ($company !== null);
    }
}
