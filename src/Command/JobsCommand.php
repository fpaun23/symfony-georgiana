<?php

namespace App\Command;

use App\Services\Jobs\JobsService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class JobsCommand extends Command
{
    private JobsService $jobsService;
    
    /**
     * __construct
     *
     * @param  mixed $jobsService
     * @return void
     */
    public function __construct(JobsService $jobsService)
    {
        $this->jobsService = $jobsService;

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
            ->setName('jobs:stats')
            ->setDescription('Retrieve statistics about jobs');
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
        $jobs = $this->jobsService->saveJobs();
        $jobsStats = [];
        foreach ($jobs as $job) {
            $jobsStats[] = $job;
        }

        $output->writeln(sprintf('Total number of jobs: %d', $jobsStats[0]));
        $output->writeln(sprintf('Number of valid jobs: %d', $jobsStats[1]));
        $output->writeln(sprintf('Number of invalid jobs: %d', $jobsStats[2]));

        return Command::SUCCESS;
    }
}
