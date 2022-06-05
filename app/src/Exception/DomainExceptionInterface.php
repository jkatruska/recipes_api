<?php

declare(strict_types=1);

namespace App\Exception;

interface DomainExceptionInterface
{
    public function getTitle(): string;

    public function getStatusCode(): int;
}
