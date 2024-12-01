<?php

namespace MahdiAbderraouf\FacturX\Models;

class Buyer
{
    private string $name;
    private string $schemeIdentifier = '0009';
    private ?string $buyerReference = null;
    private ?string $legalRegistrationIdentifier = null;

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

    public function getBuyerReference(): ?string
    {
        return $this->buyerReference;
    }

    public function setBuyerReference(?string $buyerReference): void
    {
        $this->buyerReference = $buyerReference;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getSchemeIdentifier(): ?string
    {
        return $this->schemeIdentifier;
    }

    public function setSchemeIdentifier(?string $schemeIdentifier): void
    {
        $this->schemeIdentifier = $schemeIdentifier;
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
