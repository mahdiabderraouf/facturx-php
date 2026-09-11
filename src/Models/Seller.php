<?php

namespace MahdiAbderraouf\FacturX\Models;

use MahdiAbderraouf\FacturX\Enums\SchemeIdentifier;
use MahdiAbderraouf\FacturX\Helpers\Utils;

class Seller
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
     * @param ?string $electronicAddress Electronic address (BT-34), an email or a platform routing identifier.
     * @param SchemeIdentifier|string|null $electronicAddressSchemeIdentifier Scheme of the electronic address
     *      (BT-34-1), e.g. SchemeIdentifier::EMAIL ('EM') or SchemeIdentifier::FRCTC_ELECTRONIC_ADDRESS ('0225').
     */
    public function __construct(
        public string $name,
        public string $vatIdentifier,
        public Address $address,
        string $email = '',
        SchemeIdentifier|string $schemeIdentifier = '0009',
        public ?string $legalRegistrationIdentifier = null,
        /** @var array<string> */
        public ?array $identifiers = null,
        public ?array $globalIdentifiers = null,
        public ?string $tradingName = null,
        public ?TaxRespresentative $taxRespresentative = null,
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
            vatIdentifier: $data['vatIdentifier'],
            address: Address::createFromArray($data['address']),
            schemeIdentifier: $data['schemeIdentifier'] ?? '0009',
            legalRegistrationIdentifier: $data['legalRegistrationIdentifier'] ?? null,
            identifiers: $data['identifiers'] ?? null,
            globalIdentifiers: $data['globalIdentifiers'] ?? null,
            tradingName: $data['tradingName'] ?? null,
            taxRespresentative: isset($data['taxRespresentative'])
                ? TaxRespresentative::createFromArray($data['taxRespresentative'])
                : null,
            electronicAddress: $data['electronicAddress'] ?? $data['email'] ?? null,
            electronicAddressSchemeIdentifier: $data['electronicAddressSchemeIdentifier']
                ?? $data['emailSchemeIdentifier']
                ?? null,
        );
    }
}
