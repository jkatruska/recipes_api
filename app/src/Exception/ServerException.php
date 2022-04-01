<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ServerException extends Exception implements DomainExceptionInterface
{
    use StatusCodeTrait;

    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Internal server error", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->message;
    }
}