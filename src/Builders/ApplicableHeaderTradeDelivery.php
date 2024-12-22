<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Models\Invoice;

class ApplicableHeaderTradeDelivery
{
    public static function build(Invoice $invoice): string
    {
        if (!$invoice->profile->isAtLeast(Profile::BASIC_WL) || !$invoice->delivery) {
            return '<ram:ApplicableHeaderTradeDelivery />';
        }

        $xml = '<ram:ApplicableHeaderTradeDelivery>';

        $xml .= ShipToTradeParty::build($invoice->profile, $invoice->delivery);
        $xml .= ActualDeliveryDate::build($invoice->delivery->actualDeliveryDate);
        $xml .= DespatchAdviceReferencedDocument::build($invoice->delivery->issuerAssignedID);

        $xml .= '</ram:ApplicableHeaderTradeDelivery>';
        return $xml;
    }
}
