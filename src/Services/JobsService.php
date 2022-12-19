<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\FileReaderInterface;

class JobsService
{
    public FileReaderInterface $fileReader;

    public function __construct(FileReaderInterface $fileReader)
    {
        $this->fileReader = $fileReader;
    }
}