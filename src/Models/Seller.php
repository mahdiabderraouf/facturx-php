<?php

namespace MahdiAbderraouf\FacturX\Models;

class Seller
{
    public string $name;
    public string $countryCode;
    public string $vatIndetifier;
    public string $schemeIdentifier = '0009';
    public string $taxSchemeIdentifier = 'VA';
    /** @var array<string> */
    public ?array $identifiers = null;
    public ?array $globalIndetifiers = null;
    public ?string $legalRegistrationIdentifier = null;
    public ?string $tradingName = null;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->countryCode = $data['countryCode'];
        $this->vatIndetifier = $data['vatIndetifier'];

        if (isset($data['legalRegistrationIdentifier'])) {
            $this->legalRegistrationIdentifier = $data['legalRegistrationIdentifier'];
        }
        if (isset($data['schemeIdentifier'])) {
            $this->schemeIdentifier = $data['schemeIdentifier'];
        }
        if (isset($data['identifiers'])) {
            $this->identifiers = $data['identifiers'];
        }
        if (isset($data['globalIndetifiers'])) {
            $this->globalIndetifiers = $data['globalIndetifiers'];
        }
        if (isset($data['tradingName'])) {
            $this->tradingName = $data['tradingName'];
        }
    }
}
