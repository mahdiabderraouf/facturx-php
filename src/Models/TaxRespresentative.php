<?php

namespace MahdiAbderraouf\FacturX\Models;

class TaxRespresentative
{
    public function __construct(
        public string $name,
        public string $vatIdentifier,
        public Address $address,
    ) {
    }
}
