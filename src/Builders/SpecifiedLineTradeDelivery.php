<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Models\Line;

class SpecifiedLineTradeDelivery
{
    public static function build(Line $line): string
    {
        return '<ram:SpecifiedLineTradeDelivery>' .
            Quantity::build('BilledQuantity', $line->invoicedQuantity, $line->invoicedQuantityUnit) .
            '</ram:SpecifiedLineTradeDelivery>';
    }
}
