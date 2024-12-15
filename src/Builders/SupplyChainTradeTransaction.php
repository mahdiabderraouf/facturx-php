<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Models\Invoice;

class SupplyChainTradeTransaction
{
    public static function build(Invoice $invoice, bool $isAtLeastBasicWl): string
    {
        return '<rsm:SupplyChainTradeTransaction>' .
            ApplicableHeaderTradeAgreement::build($invoice, $isAtLeastBasicWl) .
            ApplicableHeaderTradeDelivery::build($invoice, $isAtLeastBasicWl) .
            ApplicableHeaderTradeSettlement::build($invoice, $isAtLeastBasicWl) .
        '</rsm:SupplyChainTradeTransaction>';
    }
}
