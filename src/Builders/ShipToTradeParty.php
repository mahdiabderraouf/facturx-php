<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Models\Delivery;

class ShipToTradeParty
{
    public static function build(?Delivery $delivery, bool $isAtLeastBasicWl): string
    {
        if (!$delivery || $isAtLeastBasicWl) {
            return '';
        }

        $xml = '<ram:ShipToTradeParty>';

        $xml .= Identifiers::build([$delivery->locationIdentifier], $isAtLeastBasicWl);

        $xml .= GlobalIdentifiers::build([
            'identifier' => $delivery->locationGlobalIdentifier,
            'schemeIdentifier' => $delivery->locationSchemeIdentifier,
        ], $isAtLeastBasicWl);

        if ($delivery->partyName) {
            $xml .= '<ram:Name>' . $delivery->partyName . '</ram:Name>';
        }

        if ($delivery->address) {
            $xml .= PostalTradeAddress::build($delivery->address);
        }

        $xml .= '</ram:ShipToTradeParty>';
        return $xml;
    }
}
