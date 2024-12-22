<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Models\Line;

class SpecifiedLineTradeSettlement
{
    public static function build(Line $line): string
    {
        return '<ram:SpecifiedLineTradeSettlement>' .
            '<ram:TypeCode>VAT</ram:TypeCode>' .
            '<ram:CategoryCode>' . $line->vatCategory->value . '</ram:CategoryCode>' .
            '<ram:RateApplicablePercent>' . $line->vatRate . '</ram:RateApplicablePercent>' .
            BillingSpecifiedPeriod::build($line->startDate, $line->endDate) .
            SpecifiedTradeAllowanceCharge::build($line->allowances) .
            SpecifiedTradeAllowanceCharge::build($line->charges) .
            '<ram:SpecifiedTradeSettlementLineMonetarySummation>' .
            '<ram:LineTotalAmount>' . $line->totalNetPrice . '</ram:LineTotalAmount>' .
            '</ram:SpecifiedTradeSettlementLineMonetarySummation>' .
            '</ram:SpecifiedLineTradeSettlement>';
    }
}
