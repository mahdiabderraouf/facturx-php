<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Models\Invoice;

class CrossIndustryInvoice
{
    public static function build(Invoice $invoice): string
    {
        return'<rsm:CrossIndustryInvoice xmlns:qdt="urn:un:unece:uncefact:data:standard:QualifiedDataType:100"
            xmlns:ram="urn:un:unece:uncefact:data:standard:ReusableAggregateBusinessInformationEntity:100"
            xmlns:rsm="urn:un:unece:uncefact:data:standard:CrossIndustryInvoice:100"
            xmlns:udt="urn:un:unece:uncefact:data:standard:UnqualifiedDataType:100"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">' .
            ExchangedDocumentContext::build($invoice) .
            ExchangedDocument::build($invoice) .
            SupplyChainTradeTransaction::build($invoice) .
        '</rsm:CrossIndustryInvoice>';
    }
}
