<?php

namespace MahdiAbderraouf\FacturX;

use DOMDocument;
use MahdiAbderraouf\FacturX\Builders\CrossIndustryInvoice;
use MahdiAbderraouf\FacturX\Models\Invoice;

class Builder
{
    public static function build(Invoice $invoice): string
    {
        $xml = CrossIndustryInvoice::build($invoice);

        $domDocument = new DOMDocument('1.0', 'UTF-8');
        $domDocument->preserveWhiteSpace = false;
        $domDocument->formatOutput = true;

        $domDocument->loadXML($xml);

        return $domDocument->saveXML();
    }
}
