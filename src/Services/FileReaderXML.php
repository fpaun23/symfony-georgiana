<?php

declare(strict_types=1);

namespace App\Services;

use DOMDocument;
use App\Services\FileReaderInterface;

class FileReaderXML implements FileReaderInterface
{
    const FILE_NAME = 'jobs.xml';
    const FILE_PATH = __DIR__ . '/' . self::FILE_NAME;
        
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
            $dom->preserveWhiteSpace = false;
            $dom->load('/home/georgiana/symfony-georgiana/src/Services/jobs.xml');
            $jobs = $dom->getElementsByTagName('job');
            $arr = [];
            foreach ($jobs as $job) {
                $arr[] = [
                    'name' => $job->getElementsByTagName('name')->item(0)->nodeValue,
                    'description' => $job->getElementsByTagName('description')->item(0)->nodeValue,
                    'active' => $job->getElementsByTagName('active')->item(0)->nodeValue,
                    'priortiy' => $job->getElementsByTagName('priority')->item(0)->nodeValue,
                    'company' => $job->getElementsByTagName('company')->item(0)->nodeValue
                ];
            }
            return $arr;
        }
    }
}