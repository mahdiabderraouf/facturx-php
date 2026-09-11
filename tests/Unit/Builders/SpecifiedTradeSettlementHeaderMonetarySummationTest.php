<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit\Builders;

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Tests\TestCase;

class SpecifiedTradeSettlementHeaderMonetarySummationTest extends TestCase
{
    private const SUMMATION = '//ram:SpecifiedTradeSettlementHeaderMonetarySummation';

    public function test_renders_the_four_mandatory_totals_at_minimum(): void
    {
        $xml = self::buildXml(self::invoiceData('minimum'));

        $this->assertXPathCount(self::SUMMATION . '/*', 4, $xml);
        $this->assertXPathValue(self::SUMMATION . '/ram:TaxBasisTotalAmount', '80', $xml);
        $this->assertXPathValue(self::SUMMATION . '/ram:TaxTotalAmount', '20', $xml);
        $this->assertXPathValue(self::SUMMATION . '/ram:TaxTotalAmount/@currencyID', 'EUR', $xml);
        $this->assertXPathValue(self::SUMMATION . '/ram:GrandTotalAmount', '100', $xml);
        $this->assertXPathValue(self::SUMMATION . '/ram:DuePayableAmount', '22.25', $xml);
    }

    public function test_adds_line_total_prepaid_and_accounting_currency_tax_at_basic_wl(): void
    {
        $xml = self::buildXml(self::invoiceData('basicwl'));

        $this->assertXPathValue(self::SUMMATION . '/ram:LineTotalAmount', '500', $xml);
        $this->assertXPathValue(self::SUMMATION . '/ram:TotalPrepaidAmount', '600', $xml);
        $this->assertXPathCount(self::SUMMATION . '/ram:TaxTotalAmount', 2, $xml);
        $this->assertXPathValue(self::SUMMATION . '/ram:TaxTotalAmount[@currencyID="USD"]', '10', $xml);
    }

    public function test_omits_basic_wl_totals_below_basic_wl(): void
    {
        $data = self::invoiceData('basicwl');
        $data['profile'] = Profile::MINIMUM;
        $data['chargesSum'] = 50.00;
        $data['allowancesSum'] = 20.00;

        $xml = self::buildXml($data);

        $this->assertXPathMissing(self::SUMMATION . '/ram:LineTotalAmount', $xml);
        $this->assertXPathMissing(self::SUMMATION . '/ram:ChargeTotalAmount', $xml);
        $this->assertXPathMissing(self::SUMMATION . '/ram:AllowanceTotalAmount', $xml);
        $this->assertXPathMissing(self::SUMMATION . '/ram:TotalPrepaidAmount', $xml);
        $this->assertXPathCount(self::SUMMATION . '/ram:TaxTotalAmount', 1, $xml);
    }

    public function test_renders_charge_and_allowance_totals_when_set(): void
    {
        $data = self::invoiceData('basicwl');
        $data['chargesSum'] = 50.00;
        $data['allowancesSum'] = 20.00;

        $xml = self::buildXml($data);

        $this->assertXPathValue(self::SUMMATION . '/ram:ChargeTotalAmount', '50', $xml);
        $this->assertXPathValue(self::SUMMATION . '/ram:AllowanceTotalAmount', '20', $xml);
    }

    public function test_omits_charge_and_allowance_totals_when_null(): void
    {
        $xml = self::buildXml(self::invoiceData('basicwl'));

        $this->assertXPathMissing(self::SUMMATION . '/ram:ChargeTotalAmount', $xml);
        $this->assertXPathMissing(self::SUMMATION . '/ram:AllowanceTotalAmount', $xml);
    }

    public function test_renders_a_zero_prepaid_amount_but_omits_a_null_one(): void
    {
        $zero = self::invoiceData('basicwl');
        $zero['paidAmount'] = 0;
        $null = self::invoiceData('basicwl');
        unset($null['paidAmount']);

        $zeroXml = self::buildXml($zero);
        $nullXml = self::buildXml($null);

        $this->assertXPathValue(self::SUMMATION . '/ram:TotalPrepaidAmount', '0', $zeroXml);
        $this->assertXPathMissing(self::SUMMATION . '/ram:TotalPrepaidAmount', $nullXml);
    }

    public function test_omits_the_second_tax_total_when_currencies_match(): void
    {
        $data = self::invoiceData('basicwl');
        $data['vatAccountingCurrencyCode'] = 'EUR';

        $xml = self::buildXml($data);

        $this->assertXPathCount(self::SUMMATION . '/ram:TaxTotalAmount', 1, $xml);
    }
}
