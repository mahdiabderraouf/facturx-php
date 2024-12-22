<?php

namespace MahdiAbderraouf\FacturX\Models;

use DateTime;

class Payterm
{
    public function __construct(
        public ?string $paymentTerms = null,
        public ?DateTime $dueDate = null,
        public ?DateTime $mandateReferenceIdentifier = null,
    ) {
    }

    public static function createFromArray(array $data): self
    {
        return new self(
            paymentTerms: $data['paymentTerms'] ?? null,
            dueDate: $data['dueDate'] ?? null,
            mandateReferenceIdentifier: $data['mandateReferenceIdentifier'] ?? null
        );
    }
}
