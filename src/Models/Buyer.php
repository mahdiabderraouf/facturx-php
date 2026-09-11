<?php

namespace MahdiAbderraouf\FacturX\Models;

use MahdiAbderraouf\FacturX\Enums\SchemeIdentifier;
use MahdiAbderraouf\FacturX\Helpers\Utils;

class Buyer
{
    public string $schemeIdentifier = '0009';

    public string $electronicAddress = '';

    public string $electronicAddressSchemeIdentifier = 'EM';

    /** @deprecated 2.4.0 Use $electronicAddress. Kept for backward compatibility, to be removed in 3.0.0. */
    public string $email = '';

    /** @deprecated 2.4.0 Use $electronicAddressSchemeIdentifier. To be removed in 3.0.0. */
    public string $emailSchemeIdentifier = 'EM';

    /**
     * @param array<array> $globalIdentifiers Global identifiers when schemeIdentifier is known :
     *      [['id' => string, 'schemeIdentifier' => SchemeIdentifier|string], ...]
     * @param string $email @deprecated 2.4.0 Use $electronicAddress.
     * @param SchemeIdentifier|string $emailSchemeIdentifier @deprecated 2.4.0 Use $electronicAddressSchemeIdentifier.
     * @param ?string $electronicAddress Electronic address (BT-49), an email or a platform routing identifier.
     * @param SchemeIdentifier|string|null $electronicAddressSchemeIdentifier Scheme of the electronic address
     *      (BT-49-1), e.g. SchemeIdentifier::EMAIL ('EM') or SchemeIdentifier::FRCTC_ELECTRONIC_ADDRESS ('0225').
     */
    public function __construct(
        public string $name,
        public Address $address,
        string $email = '',
        SchemeIdentifier|string $schemeIdentifier = '0009',
        public ?string $legalRegistrationIdentifier = null,
        /** @var array<string> */
        public ?array $identifiers = null,
        public ?array $globalIdentifiers = null,
        public ?string $vatIdentifier = null,
        public ?string $buyerReference = null,
        public ?string $accountingReference = null,
        SchemeIdentifier|string $emailSchemeIdentifier = SchemeIdentifier::EMAIL,
        ?string $electronicAddress = null,
        SchemeIdentifier|string|null $electronicAddressSchemeIdentifier = null,
    ) {
        $this->schemeIdentifier = Utils::stringOrEnumToString($schemeIdentifier);
        $this->electronicAddress = $electronicAddress ?? $email;
        $this->electronicAddressSchemeIdentifier = Utils::stringOrEnumToString(
            $electronicAddressSchemeIdentifier ?? $emailSchemeIdentifier
        );
        $this->email = $this->electronicAddress;
        $this->emailSchemeIdentifier = $this->electronicAddressSchemeIdentifier;
    }

    public static function createFromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            address: Address::createFromArray($data['address']),
            schemeIdentifier: $data['schemeIdentifier'] ?? '0009',
            legalRegistrationIdentifier: $data['legalRegistrationIdentifier'] ?? null,
            identifiers: $data['identifiers'] ?? null,
            globalIdentifiers: $data['globalIdentifiers'] ?? null,
            vatIdentifier: $data['vatIdentifier'] ?? null,
            buyerReference: $data['buyerReference'] ?? null,
            accountingReference: $data['accountingReference'] ?? null,
            electronicAddress: $data['electronicAddress'] ?? $data['email'] ?? null,
            electronicAddressSchemeIdentifier: $data['electronicAddressSchemeIdentifier']
                ?? $data['emailSchemeIdentifier']
                ?? null,
        );
    }
}
