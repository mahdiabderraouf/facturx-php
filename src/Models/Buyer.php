<?php

namespace MahdiAbderraouf\FacturX\Models;

use MahdiAbderraouf\FacturX\Enums\SchemeIdentifier;
use MahdiAbderraouf\FacturX\Helpers\Utils;

class Buyer
{
    public string $schemeIdentifier = '0009';

    /**
     * @param array<array> $globalIdentifiers Global identifiers when schemeIdentifier is known :
     *      [['id' => string, 'schemeIdentifier' => SchemeIdentifier|string], ...]
     */
    public function __construct(
        public string $name,
        public Address $address,
        public string $email = '',
        SchemeIdentifier|string $schemeIdentifier = '0009',
        public ?string $legalRegistrationIdentifier = null,
        /** @var array<string> */
        public ?array $identifiers = null,
        public ?array $globalIdentifiers = null,
        public ?string $vatIdentifier = null,
        public ?string $buyerReference = null,
        public ?string $accountingReference = null
    ) {
        $this->schemeIdentifier = Utils::stringOrEnumToString($schemeIdentifier);
    }

    public static function createFromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            address: Address::createFromArray($data['address']),
            email: $data['email'] ?? '',
            schemeIdentifier: $data['schemeIdentifier'] ?? '0009',
            legalRegistrationIdentifier: $data['legalRegistrationIdentifier'] ?? null,
            identifiers: $data['identifiers'] ?? null,
            globalIdentifiers: $data['globalIdentifiers'] ?? null,
            vatIdentifier: $data['vatIdentifier'] ?? null,
            buyerReference: $data['buyerReference'] ?? null,
            accountingReference: $data['accountingReference'] ?? null
        );
    }
}
