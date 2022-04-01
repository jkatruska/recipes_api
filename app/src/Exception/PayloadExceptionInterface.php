<?php

declare(strict_types=1);

namespace App\Exception;

interface PayloadExceptionInterface extends DomainExceptionInterface
{
    public function getData(): array;
}
