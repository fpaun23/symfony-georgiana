<?php

declare(strict_types=1);

namespace App\Services\Jobs;

use App\Entity\Jobs;
use Psr\Log\LoggerInterface;
use App\Repository\JobsRepository;
use App\Repository\CompanyRepository;
use App\Validator\Job\JobBulkValidator;
use App\Services\File\FileReaderInterface;
use App\Services\Jobs\JobsServiceInterface;
use DateTime;

class JobsService implements JobsServiceInterface
{
    private FileReaderInterface $fileReader;
    private JobBulkValidator $jobBulkValidator;
    private LoggerInterface $logger;
    private CompanyRepository $companyRepository;
    private JobsRepository $jobsRepository;
    private int $totalJobs = 0;
    private int $invalidJobs = 0;
    private int $validJobs = 0;


    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        FileReaderInterface $fileReader,
        JobBulkValidator $jobBulkValidator,
        LoggerInterface $logger,
        CompanyRepository $companyRepository,
        JobsRepository $jobsRepository
    ) {
        $this->fileReader = $fileReader;
        $this->jobBulkValidator = $jobBulkValidator;
        $this->logger = $logger;
        $this->companyRepository = $companyRepository;
        $this->jobsRepository = $jobsRepository;
    }

    /**
     * saveJobs
     *
     * @return array
     */
    public function saveJobs(): array
    {
        $data = $this->fileReader->getData()['jobs'];
        $this->totalJobs = sizeof($data);

        foreach ($data as $dataJob) {
            $company = $this->companyRepository->find($dataJob['company_id']);

            if (!$this->jobBulkValidator->companyIsValid($company)) {
                $this->invalidJobs++;

                $this->logger->error(
                    "Error",
                    [
                        json_encode("Company with id " . $dataJob['company_id'] . " does not exist")
                    ]
                );
            } else {

                $this->validJobs++;

                $job = new Jobs();

                $job->setName($dataJob['name']);
                $job->setDescription($dataJob['description']);
                $job->setPriority((int)$dataJob['priority']);
                $job->setActive((int)$dataJob['active']);
                $job->setCompany($company);
                $job->setCreatedAt(new \DateTime("now"));

                $this->jobsRepository->save($job);
            }
        }

        return [

            "total_jobs" => $this->totalJobs,
            "valid_jobs" => $this->validJobs,
            "invalid_jobs" => $this->invalidJobs
        ];
    }
}
