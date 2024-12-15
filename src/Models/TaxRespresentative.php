<?php

namespace MahdiAbderraouf\FacturX\Models;

class TaxRespresentative
{
    public function __construct(
        public string $name,
        public string $vatIdentifier,
        public Address $address,
    ) {}

    public static function createFromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            vatIdentifier: $data['vatIdentifier'],
            address: isset($data['address']) ? Address::createFromArray($data['address']) : null,
        );
    }
}
