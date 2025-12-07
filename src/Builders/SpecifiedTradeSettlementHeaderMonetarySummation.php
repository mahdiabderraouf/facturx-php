<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Models\Invoice;

class SpecifiedTradeSettlementHeaderMonetarySummation
{
    public static function build(Invoice $invoice): string
    {
        $xml = '<ram:SpecifiedTradeSettlementHeaderMonetarySummation>';

        if ($invoice->profile->isAtLeast(Profile::BASIC_WL)) {
            $xml .= '<ram:LineTotalAmount>' . $invoice->lineNetAmount . '</ram:LineTotalAmount>';

            if ($invoice->chargesSum !== null) {
                $xml .= '<ram:ChargeTotalAmount>' . $invoice->chargesSum . '</ram:ChargeTotalAmount>';
            }

            if ($invoice->allowancesSum !== null) {
                $xml .= '<ram:AllowanceTotalAmount>' . $invoice->allowancesSum . '</ram:AllowanceTotalAmount>';
            }
        }

        $xml .= '<ram:TaxBasisTotalAmount>' . $invoice->totalAmountWithoutVAT . '</ram:TaxBasisTotalAmount>';
        $xml .= '<ram:TaxTotalAmount currencyID="' . $invoice->vatCurrency . '">'
            . $invoice->totalVATAmount . '</ram:TaxTotalAmount>';

        if ($invoice->profile->isAtLeast(Profile::BASIC_WL) && $invoice->vatAccountingCurrencyCode && $invoice->totalVATAmountInAccountingCurrency !== null) {
            $xml .= '<ram:TaxTotalAmount currencyID="' . $invoice->vatAccountingCurrencyCode . '">'
                . $invoice->totalVATAmountInAccountingCurrency . '</ram:TaxTotalAmount>';
        }

        $xml .= '<ram:GrandTotalAmount>' . $invoice->totalAmountWithVAT . '</ram:GrandTotalAmount>';

        if ($invoice->profile->isAtLeast(Profile::BASIC_WL) && $invoice->paidAmount !== null) {
            $xml .= '<ram:TotalPrepaidAmount>' . $invoice->paidAmount . '</ram:TotalPrepaidAmount>';
        }

        $xml .= '<ram:DuePayableAmount>' . $invoice->amountDueForPayment . '</ram:DuePayableAmount>';

        return $xml . '</ram:SpecifiedTradeSettlementHeaderMonetarySummation>';
    }
}
