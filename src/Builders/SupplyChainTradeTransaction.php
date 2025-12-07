<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Models\Invoice;

class SupplyChainTradeTransaction
{
    public static function build(Invoice $invoice): string
    {
        $xml = '<rsm:SupplyChainTradeTransaction>';

        if ($invoice->profile->isAtLeast(Profile::BASIC)) {
            foreach ($invoice->lines ?? [] as $line) {
                $xml .= IncludedSupplyChainTradeLineItem::build($line);
            }
        }

        return $xml . (ApplicableHeaderTradeAgreement::build($invoice) . ApplicableHeaderTradeDelivery::build($invoice) . ApplicableHeaderTradeSettlement::build($invoice) . '</rsm:SupplyChainTradeTransaction>');
    }
}
