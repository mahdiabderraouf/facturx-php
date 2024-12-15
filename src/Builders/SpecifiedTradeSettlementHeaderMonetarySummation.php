<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Models\Invoice;

class SpecifiedTradeSettlementHeaderMonetarySummation
{
    public static function build(Invoice $invoice, bool $isAtLeastBasicWl): string
    {
        $xml = '<ram:SpecifiedTradeSettlementHeaderMonetarySummation>';

        if ($isAtLeastBasicWl) {
            $xml .= '<ram:LineTotalAmount>' . $invoice->lineNetAmount . '</ram:LineTotalAmount>';

            if ($invoice->chargesSum) {
                $xml .= '<ram:ChargeTotalAmount>' . $invoice->chargesSum . '</ram:ChargeTotalAmount>';
            }

            if ($invoice->allowancesSum) {
                $xml .= '<ram:AllowanceTotalAmount>' . $invoice->allowancesSum . '</ram:AllowanceTotalAmount>';
            }
        }

        $xml .= '<ram:TaxBasisTotalAmount>' . $invoice->totalAmountWithoutVAT . '</ram:TaxBasisTotalAmount>';
        $xml .= '<ram:TaxTotalAmount currencyID="' . $invoice->vatCurrency . '">'
            . $invoice->totalVATAmount . '</ram:TaxTotalAmount>';
        $xml .= '<ram:GrandTotalAmount>' . $invoice->totalAmountWithVAT . '</ram:GrandTotalAmount>';

        if ($isAtLeastBasicWl && $invoice->paidAmount) {
            $xml .= '<ram:TotalPrepaidAmount>' . $invoice->paidAmount . '</ram:TotalPrepaidAmount>';
        }

        $xml .= '<ram:DuePayableAmount>' . $invoice->amountDueForPayment . '</ram:DuePayableAmount>';
        $xml .= '</ram:SpecifiedTradeSettlementHeaderMonetarySummation>';

        return $xml;
    }
}
