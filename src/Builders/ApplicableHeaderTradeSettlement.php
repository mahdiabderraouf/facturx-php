<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Models\Invoice;

class ApplicableHeaderTradeSettlement
{
    public static function build(Invoice $invoice): string
    {
        $xml = '<ram:ApplicableHeaderTradeSettlement>';

        if ($invoice->profile->isAtLeast(Profile::BASIC_WL)) {
            $xml .= CreditorReferenceID::build($invoice->bankAssignedCreditorIdentifier) .
                PaymentReference::build($invoice->remittanceInformation);

            // BT-6 (VAT accounting currency) must only be present when it differs from
            // BT-5 (invoice currency). Emitting it when both are equal violates rule BR-53.
            if ($invoice->vatCurrency !== $invoice->currencyCode) {
                $xml .= TaxCurrencyCode::build($invoice->vatCurrency);
            }
        }

        $xml .= '<ram:InvoiceCurrencyCode>' . $invoice->currencyCode . '</ram:InvoiceCurrencyCode>';

        if ($invoice->profile->isAtLeast(Profile::BASIC_WL)) {
            $xml .= PayeeTradeParty::build($invoice->profile, $invoice->payee) .
                SpecifiedTradeSettlementPaymentMeans::build($invoice->payment) .
                ApplicableTradeTax::build($invoice->vatBreakdowns) .
                BillingSpecifiedPeriod::build($invoice->invoicingPeriodStartDate, $invoice->invoicingPeriodEndDate) .
                SpecifiedTradeAllowanceCharge::build($invoice->allowances) .
                SpecifiedTradeAllowanceCharge::build($invoice->charges) .
                SpecifiedTradePaymentTerms::build($invoice->payterm);
        }

        $xml .= SpecifiedTradeSettlementHeaderMonetarySummation::build($invoice);

        if ($invoice->profile->isAtLeast(Profile::BASIC_WL)) {
            $xml .= InvoiceReferencedDocument::build($invoice->precedingInvoices) .
                ReceivableSpecifiedTradeAccountingAccount::build($invoice->buyer->accountingReference);
        }
        return $xml . '</ram:ApplicableHeaderTradeSettlement>';
    }
}
