<?php

namespace MahdiAbderraouf\FacturX\Exceptions;

use Exception;
use LibXMLError;
use Throwable;

class InvalidXmlException extends Exception
{
    /**
     * @param  ?array<LibXMLError> $errors
     */
    public function __construct(string $message = '', int $code = 0, Throwable|null $previous = null, private readonly ?array $errors = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getErrors(): ?array
    {
        return $this->errors;
    }
}
