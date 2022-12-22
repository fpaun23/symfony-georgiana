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
    private $data;

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
        $xml = simplexml_load_file(self::FILE_PATH);
        $json = json_encode($xml);
        $this->data = json_decode($json, true);
        $arr = ["jobs" => $this->data['job']];
        return $arr;
    }
}
