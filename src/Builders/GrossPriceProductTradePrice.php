<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Models\Line;

class GrossPriceProductTradePrice
{
    public static function build(Line $line): string
    {
        if (!$line->grossPrice) {
            return '';
        }

        $xml = '<ram:GrossPriceProductTradePrice>';
        if ($line->grossPrice) {
            $xml .= '<ram:ChargeAmount>' . $line->grossPrice . '</ram:ChargeAmount>';
        }

        $xml .= Quantity::build('BasisQuantity', $line->priceQuantity, $line->priceQuantityUnit);

        if ($line->priceDiscount) {
            $xml .= '<ram:AppliedTradeAllowanceCharge>';
            $xml .= '<ram:ChargeIndicator><udt:Indicator>false</udt:Indicator></ram:ChargeIndicator>';
            $xml .= '<ram:ActualAmount>' . $line->priceDiscount . '</ram:ActualAmount>';
            $xml .= '</ram:AppliedTradeAllowanceCharge>';
        }

        return $xml . '</ram:GrossPriceProductTradePrice>';
    }
}
