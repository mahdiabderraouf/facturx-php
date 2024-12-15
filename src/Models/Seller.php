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
        public ?TaxRespresentative $taxRespresentative = null,
    ) {
        $this->schemeIdentifier = Utils::stringOrEnumToString($schemeIdentifier);
    }

    public static function createFromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            vatIdentifier: $data['vatIdentifier'],
            address: Address::createFromArray($data['address']),
            email: $data['email'] ?? '',
            schemeIdentifier: $data['schemeIdentifier'] ?? '0009',
            legalRegistrationIdentifier: $data['legalRegistrationIdentifier'] ?? null,
            identifiers: $data['identifiers'] ?? null,
            globalIndetifiers: $data['globalIndetifiers'] ?? null,
            tradingName: $data['tradingName'] ?? null,
            taxRespresentative: isset($data['taxRespresentative']) ? TaxRespresentative::createFromArray($data['taxRespresentative']) : null,
        );
    }
}
