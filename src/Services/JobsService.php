<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\FileReaderInterface;
use App\Validator\JobBulkValidator;

class JobsService
{
    public FileReaderInterface $fileReader;
    public JobBulkValidator $jobBulkValidator;

    /**
     * __construct
     *
     * @param  mixed $fileReader
     * @param  mixed $jobBulkValidator
     * @return void
     */
    public function __construct(
        FileReaderInterface $fileReader,
        JobBulkValidator $jobBulkValidator
    ) {
        $this->fileReader = $fileReader;
        $this->jobBulkValidator = $jobBulkValidator;
    }
}
