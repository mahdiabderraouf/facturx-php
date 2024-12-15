<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Models\Invoice;

class ApplicableHeaderTradeAgreement
{
    public static function build(Invoice $invoice, bool $isAtLeastBasicWl): string
    {
        $xml = '<ram:ApplicableHeaderTradeAgreement>';

        if ($invoice->buyer->buyerReference) {
            $xml .= <<<XML
            <ram:BuyerReference>{$invoice->buyer->buyerReference}</ram:BuyerReference>
            XML;
        }

        $xml .= SellerTradeParty::build($invoice->seller, $isAtLeastBasicWl);
        $xml .= BuyerTradeParty::build($invoice->buyer, $isAtLeastBasicWl);

        $xml .= SellerTaxRepresentativeTradeParty::build($invoice->seller->taxRespresentative, $isAtLeastBasicWl);

        $xml .= BuyerOrderReferencedDocument::build($invoice->purchaseOrderReference);
        $xml .= ContractReferencedDocument::build($invoice->contractReference, $isAtLeastBasicWl);

        $xml .= '</ram:ApplicableHeaderTradeAgreement>';

        return $xml;
    }
}
