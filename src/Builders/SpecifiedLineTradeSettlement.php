<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Models\Line;

class SpecifiedLineTradeSettlement
{
    public static function build(Line $line): string
    {
        $xml = '<ram:SpecifiedLineTradeSettlement>';
        $xml .= '<ram:ApplicableTradeTax>';
        $xml .= '<ram:TypeCode>VAT</ram:TypeCode>';
        $xml .= '<ram:CategoryCode>' . $line->vatCategory->value . '</ram:CategoryCode>';
        if ($line->vatRate) {
            $xml .= '<ram:RateApplicablePercent>' . $line->vatRate . '</ram:RateApplicablePercent>';
        }
        $xml .= '</ram:ApplicableTradeTax>';

        $xml .= BillingSpecifiedPeriod::build($line->startDate, $line->endDate);
        $xml .= SpecifiedTradeAllowanceCharge::build($line->allowances);
        $xml .= SpecifiedTradeAllowanceCharge::build($line->charges);

        $xml .= '<ram:SpecifiedTradeSettlementLineMonetarySummation>';
        $xml .= '<ram:LineTotalAmount>' . $line->totalNetPrice . '</ram:LineTotalAmount>';
        $xml .= '</ram:SpecifiedTradeSettlementLineMonetarySummation>';

        $xml .= '</ram:SpecifiedLineTradeSettlement>';

        return $xml;
    }
}
