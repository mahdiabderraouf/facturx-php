<?php

namespace MahdiAbderraouf\FacturX\Models;

use DateTime;
use MahdiAbderraouf\FacturX\Enums\DeliveryLocationSchemeIdentifier;

class Delivery
{
    public function __construct(
        public ?string $locationIdentifier = null,
        public ?string $locationGlobalIdentifier = null,
        public ?DeliveryLocationSchemeIdentifier $locationSchemeIdentifier = null,
        public ?string $partyName = null,
        public ?Address $address = null,
        public ?DateTime $actualDeliveryDate = null,
        public ?string $issuerAssignedID = null,
    ) {
    }

    public static function createFromArray(array $data): self
    {
        return new self(
            locationIdentifier: $data['locationIdentifier'] ?? null,
            locationGlobalIdentifier: $data['locationGlobalIdentifier'] ?? null,
            locationSchemeIdentifier: $data['locationSchemeIdentifier'] ?? null,
            partyName: $data['partyName'] ?? null,
            address: isset($data['address']) ? Address::createFromArray($data['address']) : null,
            actualDeliveryDate: $data['actualDeliveryDate'] ?? null,
            issuerAssignedID: $data['issuerAssignedID'] ?? null
        );
    }
}
