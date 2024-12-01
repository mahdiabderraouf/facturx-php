<?php

namespace MahdiAbderraouf\FacturX\Models;

class Seller
{
    private string $name;
    private string $countryCode;
    private string $vatIndetifier;
    private string $schemeIdentifier = '0009';
    private string $taxSchemeIdentifier = 'VA';
    private ?string $legalRegistrationIdentifier = null;

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
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    public function getVatIdentifier(): string
    {
        return $this->vatIndetifier;
    }

    public function setVatIdentifier(string $vatIndetifier): void
    {
        $this->vatIndetifier = $vatIndetifier;
    }

    public function getSchemeIdentifier(): string
    {
        return $this->schemeIdentifier;
    }

    public function setSchemeIdentifier(string $schemeIdentifier): void
    {
        $this->schemeIdentifier = $schemeIdentifier;
    }

    public function getTaxSchemeIdentifier(): string
    {
        return $this->taxSchemeIdentifier;
    }

    public function setTaxSchemeIdentifier(string $taxSchemeIdentifier): void
    {
        $this->taxSchemeIdentifier = $taxSchemeIdentifier;
    }

    public function getLegalRegistrationIdentifier(): ?string
    {
        return $this->legalRegistrationIdentifier;
    }

    public function setLegalRegistrationIdentifier(?string $legalRegistrationIdentifier): void
    {
        $this->legalRegistrationIdentifier = $legalRegistrationIdentifier;
    }
}
