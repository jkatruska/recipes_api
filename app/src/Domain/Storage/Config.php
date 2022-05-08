<?php

declare(strict_types=1);

namespace App\Domain\Storage;

class Config
{
    public function __construct(
        public readonly string $endpoint,
        public readonly string $bucket,
        public readonly string $accessKey,
        public readonly string $secret
    ) {
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function getBucket(): string
    {
        return $this->bucket;
    }

    public function getAccessKey(): string
    {
        return $this->accessKey;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }
}
