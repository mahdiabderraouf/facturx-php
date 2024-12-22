<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Models\TaxRespresentative;

class SellerTaxRepresentativeTradeParty
{
    public static function build(Profile $profile, ?TaxRespresentative $taxRespresentative): string
    {
        if ($taxRespresentative) {
            $xml = '<ram:SellerTaxRepresentativeTradeParty>';

            $xml .= '<ram:Name>' . $taxRespresentative->name . '</ram:Name>';

            $xml .= PostalTradeAddress::build($taxRespresentative->address, $profile);

            $xml .= SpecifiedTaxRegistration::build($taxRespresentative->vatIdentifier);

            $xml .= '</ram:SellerTaxRepresentativeTradeParty>';
            return $xml;
        }

        return '';
    }
}
