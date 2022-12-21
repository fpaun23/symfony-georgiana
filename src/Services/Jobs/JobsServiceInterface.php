<?php

declare(strict_types=1);

namespace App\Services\Jobs;

interface JobsServiceInterface
{
    public function saveJobs(): array;
}