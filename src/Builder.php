<?php

namespace MahdiAbderraouf\FacturX;

use DOMDocument;
use MahdiAbderraouf\FacturX\Builders\CrossIndustryInvoice;
use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Models\Invoice;

class Builder
{
    public Invoice $invoice;
    public string $xml = '';
    public bool $isAtLeastBasicWl;

    public static function build(Invoice $invoice): string
    {
        $isAtLeastBasicWl = $invoice->profile
            ->isAtLeast(Profile::BASIC_WL);

        $xml = CrossIndustryInvoice::build($invoice, $isAtLeastBasicWl);

        $domDocument = new DOMDocument('1.0', 'UTF-8');
        $domDocument->preserveWhiteSpace = false;
        $domDocument->formatOutput = true;

        $domDocument->loadXML($xml);

        return $domDocument->saveXML();
    }
}
