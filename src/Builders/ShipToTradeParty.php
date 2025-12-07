<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Models\Delivery;

class ShipToTradeParty
{
    public static function build(Profile $profile, ?Delivery $delivery): string
    {
        if (!$delivery instanceof \MahdiAbderraouf\FacturX\Models\Delivery) {
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

        if ($delivery->address instanceof \MahdiAbderraouf\FacturX\Models\Address) {
            $xml .= PostalTradeAddress::build($delivery->address, $profile);
        }
        return $xml . '</ram:ShipToTradeParty>';
    }
}
