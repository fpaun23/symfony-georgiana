<?php

declare(strict_types=1);

namespace App\Services\File;

use DOMDocument;
use App\Services\File\FileReaderInterface;
use App\Validator\Job\JobBulkValidator;

class FileReaderXML implements FileReaderInterface
{
    const FILE_NAME = 'jobs.xml';
    const FILE_PATH = __DIR__ . '/' . self::FILE_NAME;
    public JobBulkValidator $validator;
    
    /**
     * __construct
     *
     * @param  mixed $validator
     * @return void
     */
    public function __construct(JobBulkValidator $validator)
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
        if (!file_exists(self::FILE_PATH)) {
            throw new \Exception('File does not exist');
        }

        $lines = file(
            self::FILE_PATH,
            FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
        );

        if (false == $lines) {
            throw new \Exception('Error reading file content.');
        }
        $dom = new DOMDocument();
        $dom->load(self::FILE_PATH);
        $arr = [];

        $jobs = $dom->getElementsByTagName('job');

        foreach ($jobs as $job) {
            $tags = array();

            foreach ($job->getElementsByTagName('*') as $tag) {
                $tags[] = $tag->tagName;
                if ($this->validator->isValid($tags)) {
                    $arr["jobs"][] = [
                        'name' => $job->getElementsByTagName('name')->item(0)->nodeValue,
                        'description' => $job->getElementsByTagName('description')->item(0)->nodeValue,
                        'active' => $job->getElementsByTagName('active')->item(0)->nodeValue,
                        'priority' => $job->getElementsByTagName('priority')->item(0)->nodeValue,
                        'company_id' =>$job->getElementsByTagName('company')->item(0)->nodeValue
                    ];
                }
            }
        }
        return $arr;
    }
}
