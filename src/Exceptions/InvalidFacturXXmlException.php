<?php

namespace MahdiAbderraouf\FacturX\Exceptions;

use Exception;
use LibXMLError;
use Throwable;

class InvalidFacturXXmlException extends Exception
{
    /** @var array<LibXMLError> */
    private ?array $errors;

    /**
     * @param  ?array<LibXMLError> $errors
     */
    public function __construct(string $message = '', int $code = 0, Throwable|null $previous = null, ?array $errors = null)
    {
        parent::__construct($message, $code, $previous);

        $this->errors = $errors;
    }

    public function getErrors(): ?array
    {
        return $this->errors;
    }
}
