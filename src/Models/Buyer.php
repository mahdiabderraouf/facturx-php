<?php

namespace MahdiAbderraouf\FacturX\Models;

use MahdiAbderraouf\FacturX\Enums\SchemeIdentifier;

class Buyer
{
    public string $schemeIdentifier = '0009';

    /**
     * @param array<array> $globalIndetifiers Global identifiers when schemeIdentifier is known :
     *      [['id' => string, 'schemeIdentifier' => SchemeIdentifier|string], ...]
     */
    public function __construct(
        public string $name,
        public string $countryCode,
        public string $postCode = '',
        public string $adress1 = '',
        public string $adress2 = '',
        public string $adress3 = '',
        public string $city = '',
        public string $province = '',
        public string $email = '',
        SchemeIdentifier|string $schemeIdentifier = '0009',
        public ?string $legalRegistrationIdentifier = null,
        /** @var array<string> */
        public ?array $identifiers = null,
        public ?array $globalIndetifiers = null,
        public ?string $buyerReference = null
    ) {
        $this->schemeIdentifier = $schemeIdentifier instanceof SchemeIdentifier ? $schemeIdentifier->value : $schemeIdentifier;
    }
}
