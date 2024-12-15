<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Models\TaxRespresentative;

class SellerTaxRepresentativeTradeParty
{
    public static function build(?TaxRespresentative $taxRespresentative, bool $isAtLeastBasicWl): string
    {
        if ($isAtLeastBasicWl && $taxRespresentative) {
            $xml = '<ram:SellerTaxRepresentativeTradeParty>';

            $xml .= '<ram:Name>' . $taxRespresentative->name . '</ram:Name>';

            $xml .= PostalTradeAddress::build($taxRespresentative->address);

            $xml .= SpecifiedTaxRegistration::build($taxRespresentative->vatIdentifier);

            $xml .= '</ram:SellerTaxRepresentativeTradeParty>';
            return $xml;
        }

        return '';
    }
}
