<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Models\Line;

class SpecifiedLineTradeAgreement
{
    public static function build(Line $line): string
    {
        return '<ram:SpecifiedLineTradeAgreement>' .
            GrossPriceProductTradePrice::build($line) .
            NetPriceProductTradePrice::build($line) .
            '</SpecifiedLineTradeAgreement>';
    }
}
