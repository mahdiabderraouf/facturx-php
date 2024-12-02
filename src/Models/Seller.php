<?php

namespace MahdiAbderraouf\FacturX\Models;

use MahdiAbderraouf\FacturX\Enums\SchemeIdentifier;
use MahdiAbderraouf\FacturX\Helpers\Utils;

class Seller
{
    public string $schemeIdentifier = '0009';

    /**
     * @param array<array> $globalIndetifiers Global identifiers when schemeIdentifier is known :
     *      [['id' => string, 'schemeIdentifier' => SchemeIdentifier|string], ...]
     */
    public function __construct(
        public string $name,
        public string $vatIdentifier,
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
        public ?Address $taxRepresentativeaddress = null,
        public ?string $contactReference = null
    ) {
        $this->schemeIdentifier = Utils::stringOrEnumToString($schemeIdentifier);
    }
}
