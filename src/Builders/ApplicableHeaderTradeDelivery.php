<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Models\Invoice;

class ApplicableHeaderTradeDelivery
{
    public static function build(Invoice $invoice, bool $isAtLeastBasicWl): string
    {
        if (!$isAtLeastBasicWl || !$invoice->delivery) {
            return '<ram:ApplicableHeaderTradeDelivery />';
        }

        $xml = '<ram:ApplicableHeaderTradeDelivery>';

        $xml .= ShipToTradeParty::build($invoice->delivery, $isAtLeastBasicWl);
        $xml .= ActualDeliveryDate::build($invoice->delivery->actualDeliveryDate, $isAtLeastBasicWl);
        $xml .= DespatchAdviceReferencedDocument::build($invoice->delivery->issuerAssignedID, $isAtLeastBasicWl);

        $xml .= '</ram:ApplicableHeaderTradeDelivery>';
        return $xml;
    }
}
