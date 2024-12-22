<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Models\Line;

class IncludedSupplyChainTradeLineItem
{
    public static function build(Line $line, Profile $profile): string
    {
        $xml = '<ram:IncludedSupplyChainTradeLineItem>
            <ram:AssociatedDocumentLineDocument>';

        $xml .= '<ram:LineID>' . $line->identifier . '</ram:LineId>';

        if ($line->note) {
            $xml .= '<ram:IncludedNote>' . $line->note . '</ram:IncludedNote>';
        }

        $xml .= '<ram:SpecifiedTradeProduct>';

        if ($line->standardIdentifier) {
            $xml .= GlobalIdentifiers::build([
                [
                    'identifier' => $line->standardIdentifier,
                    'schemeIdentifier' => $line->schemeIdentifier,
                ],
            ]);
        }

        $xml .= '<ram:Name>' . $line->name . '</ram:Name>';
        $xml .= '</ram:SpecifiedTradeProduct>';

        $xml .= '<ram:SpecifiedLineTradeAgreement>
            <ram:GrossPriceProductTradePrice>';

        if ($line->grossPrice) {
            $xml .= '<ram:ChargeAmount>' . $line->grossPrice . '</ram:ChargeAmount>';
        }

        if ($line->priceQuantity) {
            $xml .= '<ram:BasisQuantity unitCode="' . $line->priceQuantityUnit . '">' . $line->priceQuantity . '</ram:BasisQuantity>';
        }

        if ($line->priceDiscount) {
            $xml .= '<ram:AppliedTradeAllowanceCharge>';
            $xml .= '<ram:chargeIndicator><udt:Indicator>false</udt:Indicator></ram:chargeIndicator>';
            $xml .= '<ram:ActualAmount>' . $line->priceDiscount . '</ram:ActualAmount>';
            $xml .= '</ram:AppliedTradeAllowanceCharge>';
        }

        $xml .= '</ram:GrossPriceProductTradePrice>
            </ram:SpecifiedLineTradeAgreement>';

        $xml .= '</ram:AssociatedDocumentLineDocument>
            </ram:IncludedSupplyChainTradeLineItem>';

        return $xml;
    }
}
