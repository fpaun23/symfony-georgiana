<?php

declare(strict_types=1);

namespace App\Services;

use DOMDocument;
use App\Services\FileReaderInterface;
use App\Validator\BulkValidator;

class FileReaderXML implements FileReaderInterface
{
    const FILE_NAME = 'jobs.xml';
    const FILE_PATH = __DIR__ . '/' . self::FILE_NAME;
    private BulkValidator $validator;

    public function __construct(BulkValidator $validator)
    {
        $this->validator = $validator;
    }
        
    /**
     * getData
     *
     * @return array
     */
    public function getData(): array
    {
        if (!file_exists('/home/georgiana/symfony-georgiana/src/Services/jobs.xml')) {
            throw new \Exception('File does not exist');
        } else {
            $dom = new DOMDocument();
            $dom->load('/home/georgiana/symfony-georgiana/src/Services/jobs.xml');
            $jobs = $dom->getElementsByTagName('job');
            $arr = [];
            foreach ($jobs as $job) {
                $name = $job->getElementsByTagName('name')->item(0)->nodeValue;
                $description = $job->getElementsByTagName('description')->item(0)->nodeValue;
                $active = $job->getElementsByTagName('active')->item(0)->nodeValue;
                $priority = $job->getElementsByTagName('priority')->item(0)->nodeValue;
                $company = $job->getElementsByTagName('company')->item(0)->nodeValue;
                $isValid = $this->validator->isValid([$name, $description, $active, $priority]);
                if ($isValid) {
                    $arr[] = [
                        'name' => $name,
                        'description' => $description,
                        'active' => $active,
                        'priortiy' => $priority,
                        'company' => $company
                    ];
                }
            }
            return $arr;
        }
    }
}