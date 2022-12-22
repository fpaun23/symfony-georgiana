<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\JobsRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteJobsCommand extends Command
{
    protected static $defaultName = 'app:delete-jobs';

    private JobsRepository $jobsRepository;

    /**
     * __construct
     *
     * @param  mixed $entityManager
     * @return void
     */
    public function __construct(JobsRepository $jobsRepository)
    {
        $this->jobsRepository = $jobsRepository;

        parent::__construct();
    }

    /**
     * configure
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setDescription('Deletes all jobs from the database');
    }

    /**
     * execute
     *
     * @param  mixed $input
     * @param  mixed $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Deleting all jobs from the database...');

        $jobs = $this->jobsRepository->findAll();

        if ($jobs) {
            foreach ($jobs as $job) {
                $this->jobsRepository->remove($job);
            }

            $output->writeln('All jobs were deleted successfully!');

            return Command::SUCCESS;
        } else {
            $output->writeln('No jobs found in the database.');
            return Command::FAILURE;
        }
    }
}