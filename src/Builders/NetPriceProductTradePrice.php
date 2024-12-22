<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Models\Line;

class NetPriceProductTradePrice
{
    public static function build(Line $line): string
    {
        return '<ram:NetPriceProductTradePrice>' .
            '<ram:ChargeAmount>' . $line->netPrice . '</ram:ChargeAmount>' .
            Quantity::build('BasisQuantity', $line->priceQuantity, $line->priceQuantityUnit) .
            '</ram:NetPriceProductTradePrice>';
    }
}
