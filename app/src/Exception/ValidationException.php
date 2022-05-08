<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Throwable;

class ValidationException extends Exception
{
    public function __construct(string $message = 'Invalid data provided', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
