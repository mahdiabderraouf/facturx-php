<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Models\Invoice;

class ExchangedDocumentContext
{
    public static function build(Invoice $invoice): string
    {
        return <<<XML
        <rsm:ExchangedDocumentContext>
            <ram:BusinessProcessSpecifiedDocumentContextParameter>
                <ram:ID>{$invoice->businessProcessType}</ram:ID>
            </ram:BusinessProcessSpecifiedDocumentContextParameter>
            <ram:GuidelineSpecifiedDocumentContextParameter>
                <ram:ID>{$invoice->profile->value}</ram:ID>
            </ram:GuidelineSpecifiedDocumentContextParameter>
        </rsm:ExchangedDocumentContext>
        XML;
    }
}
