<?php

namespace MahdiAbderraouf\FacturX\Models;

use DateTime;

class Payterm
{
    public function __construct(
        public ?string $paymentTerms = null,
        public ?DateTime $dueDate = null,
        public ?DateTime $mandateReferenceIdentifier = null,
    ) {}
}
