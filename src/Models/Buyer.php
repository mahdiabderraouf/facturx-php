<?php

namespace MahdiAbderraouf\FacturX\Models;

use MahdiAbderraouf\FacturX\Enums\SchemeIdentifier;
use MahdiAbderraouf\FacturX\Helpers\Utils;

class Buyer
{
    public string $schemeIdentifier = '0009';

    /**
     * @param array<array> $globalIndetifiers Global identifiers when schemeIdentifier is known :
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
        public ?array $globalIndetifiers = null,
        public ?string $vatIdentifier = null,
        public ?string $buyerReference = null,
        public ?string $accountingReference = null
    ) {
        $this->schemeIdentifier = Utils::stringOrEnumToString($schemeIdentifier);
    }
}
