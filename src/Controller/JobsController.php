<?php

declare(strict_types=1);

namespace App\Controller;

use DateTime;
use Exception;
use App\Entity\Jobs;
use App\Repository\JobsRepository;
use App\Repository\CompanyRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Services\Jobs\JobsService;

/**
 * JobsController
 */
class JobsController extends AbstractController
{
    private JobsRepository $jobsRepository;
    private CompanyRepository $companyRepository;
    private JobsService $jobsService;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        JobsRepository $jobsRepository,
        CompanyRepository $companyRepository,
        JobsService $jobsService
    ) {
        $this->jobsRepository = $jobsRepository;
        $this->companyRepository = $companyRepository;
        $this->jobsService = $jobsService;
    }

    /**
     * addJob
     *
     * @param  mixed $request
     * @return Response
     */
    public function addJob(Request $request): Response
    {
        try {
            $name = $request->get("name");
            $active = (int)$request->get('active');
            $description = $request->get("description");
            $priority = (int)$request->get('priority');
            $companyId = (int)$request->get('company_id');

            $company =  $this->companyRepository->find($companyId);

            if (is_null($company)) {
                throw new \InvalidArgumentException('Could not find job with id: ' . $companyId);
            }

            //create new Jobs objec
            $job = new Jobs();
            $job->setName($name);
            $job->setDescription($description);
            $job->setActive($active);
            $job->setPriority($priority);
            $job->setCreatedAt(new DateTime());
            $job->setCompany($company);

            $savedJob = $this->jobsRepository->save($job);

            return new JsonResponse(
                [
                    'results' => [
                        'added' => $savedJob,
                        'error' => false,
                    ]
                ]
            );
        } catch (Exception $e) {
            return new JsonResponse(
                [
                    'results' => [
                        'error' => true,
                        'message' => $e->getMessage()
                    ]
                ]
            );
        }
    }

    /**
     * getJobById
     *
     * @param  mixed $id
     * @return Response
     */
    public function getJobById(int $id): Response
    {
        try {
            $job = $this->jobsRepository->find($id);

            if (null === $job) {
                throw new \InvalidArgumentException('Could not find job with id: ' . $id);
            }

            return new JsonResponse(
                [
                    'results' => [
                        'jobs' => [
                            'id' => $job->getId(),
                            'name' => $job->getName(),
                            'priority' => $job->getPriority(),
                            'active' => $job->getActive(),
                            'description' => $job->getDescription(),
                            'company' => [
                                'id' => $job->getCompany()->getId(),
                                'name' => $job->getCompany()->getName()
                            ]
                        ],
                        'error' => false,
                    ]
                ]
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                [
                    'result' => [
                        'results' => [
                            'error' => true,
                            'message' => $e->getMessage()
                        ]
                    ]
                ]
            );
        }
    }

    /**
     * updateJobs
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return Response
     */
    public function updateJob(Request $request, int $id): Response
    {
        try {
            $requestParams = $request->query->all();

            $updateResult = $this->jobsRepository->update($id, $requestParams);

            return new JsonResponse(
                [
                    'results' => [
                        'updated' => $updateResult,
                        'error' => false,
                    ]
                ]
            );
        } catch (Exception $e) {
            return new JsonResponse(
                [
                    'results' => [
                        'error' => true,
                        'message' => $e->getMessage()
                    ]
                ]
            );
        }
    }

    /**
     * getJobList
     *
     * @return Response
     */
    public function getJobList(): Response
    {
        $jobs = $this->jobsRepository->findAll();

        $name = [];

        foreach ($jobs as $job) {
            $name[] = [
                'id' => $job->getId(),
                'name' => $job->getName(),
                'description' => $job->getDescription(),
                'active' => $job->getActive(),
                'priortiy' => $job->getPriority(),
                'createdAt' => $job->getCreatedAt(),
                'company' => [
                    'id' => $job->getCompany()->getId(),
                    'company' => $job->getCompany()->getName()

                ]
            ];
        }

        return new JsonResponse(
            [
                'results' => [
                    'error' => false,
                    'jobs' => $name
                ]
            ]
        );
    }

    /**
     * deleteJob
     *
     * @param  mixed $id
     * @return Response
     */
    public function deleteJob(int $id): Response
    {
        $job = $this->jobsRepository->find($id);

        if (is_null($job)) {
            throw new \InvalidArgumentException('Could not find job with id: ' . $id);
        }

        try {
            $this->jobsRepository->remove($job);
            return new JsonResponse(
                [
                    'results' => [
                        'deleted' => $id,
                        'error' => false,
                    ]
                ]
            );
        } catch (Exception $e) {
            return new JsonResponse(
                [
                    'results' => [
                        'error' => true,
                        'message' => $e->getMessage()
                    ]
                ]
            );
        }
    }


    /**
     * getJobByName
     *
     * @param  mixed $name
     * @return Response
     */
    public function getJobByName(string $name): Response
    {
        try {
            $result = $this->jobsRepository->getJobByName($name);

            $arr = [];
            foreach ($result as $job) {
                $arr[] = [
                    'id' => $job->getId(),
                    'name' => $job->getName(),
                    'description' => $job->getDescription(),
                    'active' => $job->getActive(),
                    'priortiy' => $job->getPriority(),
                    'createdAt' => $job->getCreatedAt(),
                    'company' => [
                        'id' => $job->getCompany()->getId(),
                        'company' => $job->getCompany()->getName()

                    ]
                ];
            }

            return new JsonResponse(
                [
                    'results' => [
                        'error' => false,
                        'jobs' => $arr
                    ]
                ]
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                [
                    'results' => [
                        'error' => true,
                        'message' => $e->getMessage()
                    ]
                ]
            );
        }
    }


    /**
     * getLikeJobName
     *
     * @param  mixed $name
     * @return Response
     */
    public function getLikeJobName(string $name): Response
    {
        try {
            $result = $this->jobsRepository->getLikeJobName($name);

            $arr = [];
            foreach ($result as $job) {
                $arr[] = [
                    'id' => $job->getId(),
                    'name' => $job->getName(),
                    'description' => $job->getDescription(),
                    'active' => $job->getActive(),
                    'priortiy' => $job->getPriority(),
                    'createdAt' => $job->getCreatedAt(),
                    'company' => [
                        'id' => $job->getCompany()->getId(),
                        'company' => $job->getCompany()->getName()

                    ]
                ];
            }

            return new JsonResponse(
                [
                    'results' => [
                        'error' => false,
                        'jobs' => $arr
                    ]
                ]
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                [
                    'results' => [
                        'error' => true,
                        'message' => $e->getMessage()
                    ]
                ]
            );
        }
    }
    
    /**
     * bulk
     *
     * @return Response
     */
    public function bulk(): Response
    {
        try {
            $data = $this->jobsService->saveJobs();

            return new JsonResponse($data);
        } catch (\Exception $e) {
            return new JsonResponse(
                [
                    'results' => [
                        'error' => true,
                        'message' => $e->getMessage()
                    ]
                ]
            );
        }
    }
}
