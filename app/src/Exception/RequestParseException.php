<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Throwable;

class RequestParseException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = 'Invalid body provided', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
