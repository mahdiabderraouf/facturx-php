<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Models\Line;

class IncludedSupplyChainTradeLineItem
{
    public static function build(Line $line): string
    {
        return '<ram:IncludedSupplyChainTradeLineItem>' .
        AssociatedDocumentLineDocument::build($line) .
            SpecifiedTradeProduct::build($line) .
            SpecifiedLineTradeAgreement::build($line) .
            SpecifiedLineTradeDelivery::build($line) .
            SpecifiedLineTradeSettlement::build($line) .
            '</ram:IncludedSupplyChainTradeLineItem>';
    }
}
