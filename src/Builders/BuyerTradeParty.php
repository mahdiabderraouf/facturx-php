<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Models\Buyer;

class BuyerTradeParty
{
    public static function build(Buyer $buyer, bool $isAtLeastBasicWl): string
    {
        if (!$buyer) {
            return '';
        }

        $xml = '<ram:BuyerTradeParty>';

        $xml .= Identifiers::build($buyer->identifiers, $isAtLeastBasicWl) .
            GlobalIdentifiers::build($buyer->globalIdentifiers, $isAtLeastBasicWl);

        if ($buyer->name) {
            $xml .= '<ram:Name>' . $buyer->name . '</ram:Name>';
        }

        $xml .= SpecifiedLegalOrganization::build($isAtLeastBasicWl, $buyer->legalRegistrationIdentifier, $buyer->schemeIdentifier);

        if ($isAtLeastBasicWl) {
            $xml .= PostalTradeAddress::build($buyer->address, $isAtLeastBasicWl);
            $xml .= Email::build($isAtLeastBasicWl, $buyer->email);
            $xml .= SpecifiedTaxRegistration::build($buyer->vatIdentifier);
        }

        $xml .= '</ram:BuyerTradeParty>';
        return $xml;
    }
}
