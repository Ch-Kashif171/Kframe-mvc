<?php

namespace Core\Exception\Handlers;

use Exception;

class ValidationException extends Exception
{
    protected array $errors;

    public function __construct(string $message = "Validation failed", array $errors = [])
    {
        parent::__construct($message, 422);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
