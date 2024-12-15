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
}
