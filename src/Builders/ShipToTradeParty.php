<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Models\Delivery;

class ShipToTradeParty
{
    public static function build(Profile $profile, ?Delivery $delivery): string
    {
        if (!$delivery) {
            return '';
        }

        $xml = '<ram:ShipToTradeParty>';

        $xml .= Identifiers::build([$delivery->locationIdentifier]);

        $xml .= GlobalIdentifiers::build([[
                'identifier' => $delivery->locationGlobalIdentifier,
                'schemeIdentifier' => $delivery->locationSchemeIdentifier,
            ]
        ]);

        if ($delivery->partyName) {
            $xml .= '<ram:Name>' . $delivery->partyName . '</ram:Name>';
        }

        if ($delivery->address) {
            $xml .= PostalTradeAddress::build($delivery->address, $profile);
        }

        $xml .= '</ram:ShipToTradeParty>';
        return $xml;
    }
}
