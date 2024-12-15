<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Models\Invoice;

class ApplicableHeaderTradeSettlement
{
    public static function build(Invoice $invoice, bool $isAtLeastBasicWl): string
    {
        $xml = '<ram:ApplicableHeaderTradeSettlement>';

        $xml .= CreditorReferenceID::build($invoice->bankAssignedCreditorIdentifier, $isAtLeastBasicWl);
        $xml .= PaymentReference::build($invoice->remittanceInformation, $isAtLeastBasicWl);
        $xml .= TaxCurrencyCode::build($invoice->vatAccountingCurrencyCode, $isAtLeastBasicWl);
        $xml .= '<ram:InvoiceCurrencyCode>' . $invoice->currencyCode . '</ram:InvoiceCurrencyCode>';
        $xml .= PayeeTradeParty::build($invoice->payee, $isAtLeastBasicWl);
        $xml .= SpecifiedTradeSettlementPaymentMeans::build($invoice->payment, $isAtLeastBasicWl);
        $xml .= ApplicableTradeTax::build($invoice->vatBreakdowns, $isAtLeastBasicWl);
        $xml .= BillingSpecifiedPeriod::build($invoice->invoicingPeriodStartDate, $invoice->invoicingPeriodEndDate, $isAtLeastBasicWl);
        $xml .= SpecifiedTradeAllowanceCharge::build($invoice->allowances, $isAtLeastBasicWl);
        $xml .= SpecifiedTradeAllowanceCharge::build($invoice->charges, $isAtLeastBasicWl);
        $xml .= SpecifiedTradePaymentTerms::build($invoice->payterm, $isAtLeastBasicWl);
        $xml .= SpecifiedTradeSettlementHeaderMonetarySummation::build($invoice, $isAtLeastBasicWl);
        $xml .= InvoiceReferencedDocument::build($invoice->precedingInvoices, $isAtLeastBasicWl);
        $xml .= ReceivableSpecifiedTradeAccountingAccount::build($invoice->buyer->accountingReference, $isAtLeastBasicWl);

        $xml .= '</ram:ApplicableHeaderTradeSettlement>';
        return $xml;
    }
}
