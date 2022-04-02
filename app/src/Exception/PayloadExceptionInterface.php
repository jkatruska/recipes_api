<?php

declare(strict_types=1);

namespace App\Exception;

interface PayloadExceptionInterface extends DomainExceptionInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getData(): array;
}
