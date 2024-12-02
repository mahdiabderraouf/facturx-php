<?php

namespace MahdiAbderraouf\FacturX\Models;

use DateTime;
use InvalidArgumentException;
use MahdiAbderraouf\FacturX\Enums\DeliveryLocationSchemeIdentifier;
use MahdiAbderraouf\FacturX\Enums\SchemeIdentifier;

class Seller
{
    public string $schemeIdentifier = '0009';

    /**
     * @param array<array> $globalIndetifiers Global identifiers when schemeIdentifier is known :
     *      [['id' => string, 'schemeIdentifier' => SchemeIdentifier|string], ...]
     */
    public function __construct(
        public string $name,
        public string $vatIndetifier,
        public Address $address,
        public string $email = '',
        SchemeIdentifier|string $schemeIdentifier = '0009',
        public ?string $legalRegistrationIdentifier = null,
        /** @var array<string> */
        public ?array $identifiers = null,
        public ?array $globalIndetifiers = null,
        public ?string $tradingName = null,
        public ?string $taxRepresentativeName = null,
        public ?string $taxRepresentativeVatIdentifier = null,
        public ?Address $taxRepresentativeAdress = null,
        public ?string $contactReference = null,
        public ?string $deliverToLocationIdentifier = null,
        public ?string $deliverToLocationGlobalIdentifier = null,
        public ?DeliveryLocationSchemeIdentifier $deliverToLocationGlobalIdentifierScheme = null,
        public ?string $deliverToPartyName = null,
        public ?Address $deliverToAdress = null,
        public ?DateTime $actualDeliveryDate = null,
        public ?string $issuerAssignedID = null
    ) {
        $this->schemeIdentifier = $schemeIdentifier instanceof SchemeIdentifier ? $schemeIdentifier->value : $schemeIdentifier;
    }
}
