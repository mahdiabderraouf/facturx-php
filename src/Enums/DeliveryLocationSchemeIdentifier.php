<?php

namespace MahdiAbderraouf\FacturX\Enums;

/**
 * Scheme identifiers for the delivery location global identifier (BT-71-1).
 *
 * Non-exhaustive list of ISO 6523 ICD codes. The value is emitted as the
 * schemeID attribute of ShipToTradeParty/GlobalID and must belong to the
 * ISO 6523 code list, otherwise the invoice fails Factur-X validation.
 */
enum DeliveryLocationSchemeIdentifier: string
{
    case SIRET = '0009';
    case SIREN = '0002';
    case GLN = '0088';
}
