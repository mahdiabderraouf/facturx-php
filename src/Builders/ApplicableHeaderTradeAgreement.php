<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Models\Invoice;

class ApplicableHeaderTradeAgreement
{
    public static function build(Invoice $invoice): string
    {
        $xml = '<ram:ApplicableHeaderTradeAgreement>';

        if ($invoice->buyer->buyerReference) {
            $xml .= <<<XML
            <ram:BuyerReference>{$invoice->buyer->buyerReference}</ram:BuyerReference>
            XML;
        }

        $xml .= SellerTradeParty::build($invoice->seller, $invoice->profile);
        $xml .= BuyerTradeParty::build($invoice->buyer, $invoice->profile);

        if ($invoice->profile->isAtLeast(Profile::BASIC_WL)) {
            $xml .= SellerTaxRepresentativeTradeParty::build($invoice->profile, $invoice->seller->taxRespresentative);
        }

        $xml .= BuyerOrderReferencedDocument::build($invoice->purchaseOrderReference);

        if ($invoice->profile->isAtLeast(Profile::BASIC_WL)) {
            $xml .= ContractReferencedDocument::build($invoice->contractReference);
        }

        $xml .= '</ram:ApplicableHeaderTradeAgreement>';

        return $xml;
    }
}
