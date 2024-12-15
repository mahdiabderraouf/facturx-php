<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Models\Seller;

class SellerTradeParty
{
    public static function build(Seller $seller, bool $isAtLeastBasicWl): string
    {
        $xml = '<ram:SellerTradeParty>';

        $xml .= Identifiers::build($seller->identifiers, $isAtLeastBasicWl) .
            GlobalIdentifiers::build($seller->globalIndetifiers, $isAtLeastBasicWl);

        $xml .= '<ram:Name>' . $seller->name . '</ram:Name>';

        $xml .= SpecifiedLegalOrganization::build(
            $seller->legalRegistrationIdentifier,
            $seller->tradingName
        );

        $xml .= PostalTradeAddress::build($seller->address);

        $xml .= Email::build($seller->email);

        $xml .= SpecifiedTaxRegistration::build($seller->vatIdentifier);

        $xml .= '</ram:SellerTradeParty>';
        return $xml;
    }
}
