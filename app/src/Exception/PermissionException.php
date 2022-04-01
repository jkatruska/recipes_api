<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class PermissionException extends Exception implements DomainExceptionInterface
{
    use StatusCodeTrait;

    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Insufficient permission", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->statusCode = Response::HTTP_FORBIDDEN;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->message;
    }
}
