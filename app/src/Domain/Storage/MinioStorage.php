<?php

declare(strict_types=1);

namespace App\Domain\Storage;

use Aws\S3\S3Client;

class MinioStorage implements StorageInterface
{
    private Config $config;
    private S3Client $client;

    public function __construct(array $config)
    {
        $this->config = new Config(
            $config['endpoint'],
            $config['bucket'],
            $config['user'],
            $config['secret']
        );

        $this->client = new S3Client([
            'version' => 'latest',
            'region' => 'eu-west-1',
            'endpoint' => $this->config->getEndpoint(),
            'use_path_style_endpoint' => true,
            'credentials' => [
                'key' => $this->config->getAccessKey(),
                'secret' => $this->config->getSecret(),
            ],
        ]);
    }

    public function get(string $key): ?File
    {
        if (!$this->client->doesObjectExist($this->config->getBucket(), $key)) {
            return null;
        }
        $command = $this->client->getCommand('GetObject', ['Bucket' => $this->config->getBucket(), 'Key' => $key]);
        $preSignedRequest = $this->client->createPresignedRequest($command, '+10 minutes');

        $file = new File();
        $file->setKey($key);
        $file->setUrl((string) $preSignedRequest->getUri());

        return $file;
    }

    public function save(array $file): File
    {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $key = time() . '_' . urlencode(substr($file['name'], 0, 16)) . '.' . $ext;
        $this->client->putObject([
            'Bucket' => $this->config->getBucket(),
            'ContentType' => $file['type'],
            'Key' => $key,
            'SourceFile' => $file['tmp_name'],
        ]);

        $file = new File();
        $file->setKey($key);

        return $file;
    }
}
