<?php

namespace MahdiAbderraouf\FacturX\Models;

class Buyer
{
    public string $name;
    public string $schemeIdentifier = '0009';
    public ?string $buyerReference = null;
    public ?string $legalRegistrationIdentifier = null;

    public function __construct(array $data)
    {
        $this->name = $data['name'];

        if (isset($data['legalRegistrationIdentifier'])) {
            $this->legalRegistrationIdentifier = $data['legalRegistrationIdentifier'];
        }

        if (isset($data['buyerReference'])) {
            $this->buyerReference = $data['buyerReference'] ?? '';
        }

        if (isset($data['schemeIdentifier'])) {
            $this->schemeIdentifier = $data['schemeIdentifier'];
        }
    }
}
