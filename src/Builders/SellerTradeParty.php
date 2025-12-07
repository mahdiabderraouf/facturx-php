<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Models\Seller;

class SellerTradeParty
{
    public static function build(Seller $seller, Profile $profile): string
    {
        $xml = '<ram:SellerTradeParty>';

        if ($profile->isAtLeast(Profile::BASIC_WL)) {
            $xml .= Identifiers::build($seller->identifiers) .
                GlobalIdentifiers::build($seller->globalIdentifiers);
        }

        $xml .= '<ram:Name>' . $seller->name . '</ram:Name>';

        $xml .= SpecifiedLegalOrganization::build(
            $profile,
            $seller->legalRegistrationIdentifier,
            $seller->schemeIdentifier,
            $seller->tradingName
        );

        $xml .= PostalTradeAddress::build($seller->address, $profile);

        if ($profile->isAtLeast(Profile::BASIC_WL)) {
            $xml .= Email::build($seller->email);
        }

        $xml .= SpecifiedTaxRegistration::build($seller->vatIdentifier);
        return $xml . '</ram:SellerTradeParty>';
    }
}
