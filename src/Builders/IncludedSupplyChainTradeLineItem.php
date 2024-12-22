<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Models\Line;

class IncludedSupplyChainTradeLineItem
{
    public static function build(Line $line): string
    {
        $xml = '<ram:IncludedSupplyChainTradeLineItem>
            <ram:AssociatedDocumentLineDocument>';

        $xml .= '<ram:LineID>' . $line->identifier . '</ram:LineId>';

        if ($line->note) {
            $xml .= '<ram:IncludedNote>' . $line->note . '</ram:IncludedNote>';
        }

        $xml .= SpecifiedTradeProduct::build($line) .
            SpecifiedLineTradeAgreement::build($line) .
            SpecifiedLineTradeDelivery::build($line) .
            SpecifiedLineTradeSettlement::build($line);

        $xml .= '</ram:AssociatedDocumentLineDocument>
            </ram:IncludedSupplyChainTradeLineItem>';

        return $xml;
    }
}
