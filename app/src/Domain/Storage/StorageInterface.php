<?php

declare(strict_types=1);

namespace App\Domain\Storage;

interface StorageInterface
{
    public function get(string $key): ?File;

    public function save(array $file): File;
}
