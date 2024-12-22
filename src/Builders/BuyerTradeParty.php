<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Models\Buyer;

class BuyerTradeParty
{
    public static function build(Buyer $buyer, Profile $profile): string
    {
        if (!$buyer) {
            return '';
        }

        $xml = '<ram:BuyerTradeParty>';

        if ($profile->isAtLeast(Profile::BASIC_WL)) {
            $xml .= Identifiers::build($buyer->identifiers) .
                GlobalIdentifiers::build($buyer->globalIdentifiers);
        }

        if ($buyer->name) {
            $xml .= '<ram:Name>' . $buyer->name . '</ram:Name>';
        }

        $xml .= SpecifiedLegalOrganization::build($profile, $buyer->legalRegistrationIdentifier, $buyer->schemeIdentifier);

        if ($profile->isAtLeast(Profile::BASIC_WL)) {
            $xml .= PostalTradeAddress::build($buyer->address, $profile);
            $xml .= Email::build($buyer->email);
            $xml .= SpecifiedTaxRegistration::build($buyer->vatIdentifier);
        }

        $xml .= '</ram:BuyerTradeParty>';
        return $xml;
    }
}
