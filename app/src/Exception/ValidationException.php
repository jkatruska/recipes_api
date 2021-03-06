<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

class ValidationException extends Exception implements PayloadExceptionInterface
{
    use StatusCodeTrait;

    private ConstraintViolationListInterface $constraintViolationList;

    public function __construct(string $message = 'Invalid data provided', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @param ConstraintViolationListInterface $constraintViolationList
     */
    public function setConstraintViolationList(ConstraintViolationListInterface $constraintViolationList): void
    {
        $this->constraintViolationList = $constraintViolationList;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->message;
    }

    /**
     * {@inheritDoc}
     */
    public function getData(): array
    {
        $payload = [];
        /** @var ConstraintViolationInterface $constraintViolation */
        foreach ($this->constraintViolationList as $constraintViolation) {
            $property = $this->formatPropertyPath($constraintViolation->getPropertyPath());
            $payload[$property] = $constraintViolation->getMessage();
        }
        return $payload;
    }

    /**
     * @param string $propertyPath
     * @return string
     */
    private function formatPropertyPath(string $propertyPath): string
    {
        return str_replace(['][', '[', ']'], ['.', '', ''], $propertyPath);
    }
}
