<?php

namespace MahdiAbderraouf\FacturX\Models;

use MahdiAbderraouf\FacturX\Enums\SchemeIdentifier;
use MahdiAbderraouf\FacturX\Helpers\Utils;

class Buyer
{
    public string $schemeIdentifier = '0009';

    public string $emailSchemeIdentifier = 'EM';

    /**
     * @param array<array> $globalIdentifiers Global identifiers when schemeIdentifier is known :
     *      [['id' => string, 'schemeIdentifier' => SchemeIdentifier|string], ...]
     * @param SchemeIdentifier|string $emailSchemeIdentifier Scheme of the electronic address (BT-49-1),
     *      e.g. SchemeIdentifier::EMAIL ('EM') or SchemeIdentifier::FRCTC_ELECTRONIC_ADDRESS ('0225').
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
        public ?string $accountingReference = null,
        SchemeIdentifier|string $emailSchemeIdentifier = SchemeIdentifier::EMAIL,
    ) {
        $this->schemeIdentifier = Utils::stringOrEnumToString($schemeIdentifier);
        $this->emailSchemeIdentifier = Utils::stringOrEnumToString($emailSchemeIdentifier);
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
            accountingReference: $data['accountingReference'] ?? null,
            emailSchemeIdentifier: $data['emailSchemeIdentifier'] ?? SchemeIdentifier::EMAIL,
        );
    }
}
