<?php

namespace MahdiAbderraouf\FacturX\Models;

use MahdiAbderraouf\FacturX\Enums\SchemeIdentifier;
use MahdiAbderraouf\FacturX\Helpers\Utils;

class Payee
{
    public string $globalIdentifierSchemeIdentifier = '';

    public string $legalRegistrationSchemeIdentifier = '';

    public function __construct(
        public string $name,
        public ?string $identifier = '',
        public ?string $globalIdentifier = '',
        SchemeIdentifier|string|null $globalIdentifierSchemeIdentifier = '',
        public ?string $legalRegistrationIdentifier = '',
        SchemeIdentifier|string|null $legalRegistrationSchemeIdentifier = '',
    ) {
        $this->globalIdentifierSchemeIdentifier = Utils::stringOrEnumToString($globalIdentifierSchemeIdentifier);
        $this->legalRegistrationSchemeIdentifier = Utils::stringOrEnumToString($legalRegistrationSchemeIdentifier);
    }

    public static function createFromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            identifier: $data['identifier'] ?? '',
            globalIdentifier: $data['globalIdentifier'] ?? '',
            globalIdentifierSchemeIdentifier: $data['globalIdentifierSchemeIdentifier'] ?? '',
            legalRegistrationIdentifier: $data['legalRegistrationIdentifier'] ?? '',
            legalRegistrationSchemeIdentifier: $data['legalRegistrationSchemeIdentifier'] ?? ''
        );
    }
}
