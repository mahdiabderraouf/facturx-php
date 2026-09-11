<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit\Builders;

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Tests\TestCase;

class ApplicableHeaderTradeSettlementTest extends TestCase
{
    private const SETTLEMENT = '//ram:ApplicableHeaderTradeSettlement';

    public function test_keeps_only_currency_and_monetary_summation_below_basic_wl(): void
    {
        $data = self::invoiceData('basicwl');
        $data['profile'] = Profile::MINIMUM;

        $xml = self::buildXml($data);

        $this->assertXPathCount(self::SETTLEMENT . '/*', 2, $xml);
        $this->assertXPathValue(self::SETTLEMENT . '/ram:InvoiceCurrencyCode', 'EUR', $xml);
        $this->assertXPathCount(self::SETTLEMENT . '/ram:SpecifiedTradeSettlementHeaderMonetarySummation', 1, $xml);
    }

    public function test_includes_every_settlement_section_at_basic_wl(): void
    {
        $xml = self::buildXml(self::invoiceData('basicwl'));

        $this->assertXPathValue(self::SETTLEMENT . '/ram:CreditorReferenceID', 'BANK-12345', $xml);
        $this->assertXPathValue(self::SETTLEMENT . '/ram:PaymentReference', 'Invoice F-2024-12-15-0002', $xml);
        $this->assertXPathValue(self::SETTLEMENT . '/ram:TaxCurrencyCode', 'USD', $xml);
        $this->assertXPathCount(self::SETTLEMENT . '/ram:PayeeTradeParty', 1, $xml);
        $this->assertXPathCount(self::SETTLEMENT . '/ram:SpecifiedTradeSettlementPaymentMeans', 1, $xml);
        $this->assertXPathCount(self::SETTLEMENT . '/ram:ApplicableTradeTax', 1, $xml);
        $this->assertXPathCount(self::SETTLEMENT . '/ram:BillingSpecifiedPeriod', 1, $xml);
        $this->assertXPathCount(self::SETTLEMENT . '/ram:SpecifiedTradeAllowanceCharge', 2, $xml);
        $this->assertXPathCount(self::SETTLEMENT . '/ram:SpecifiedTradePaymentTerms', 1, $xml);
        $this->assertXPathCount(self::SETTLEMENT . '/ram:InvoiceReferencedDocument', 1, $xml);
        $this->assertXPathValue(
            self::SETTLEMENT . '/ram:ReceivableSpecifiedTradeAccountingAccount/ram:ID',
            'ACCT-1001',
            $xml
        );
    }

    public function test_omits_tax_currency_when_it_equals_the_invoice_currency(): void
    {
        $data = self::invoiceData('basicwl');
        $data['vatAccountingCurrencyCode'] = 'EUR';

        $xml = self::buildXml($data);

        $this->assertXPathMissing(self::SETTLEMENT . '/ram:TaxCurrencyCode', $xml);
    }

    public function test_omits_tax_currency_without_a_vat_amount_in_that_currency(): void
    {
        $data = self::invoiceData('basicwl');
        unset($data['totalVATAmountInAccountingCurrency']);

        $xml = self::buildXml($data);

        $this->assertXPathMissing(self::SETTLEMENT . '/ram:TaxCurrencyCode', $xml);
    }
}
