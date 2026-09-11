<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit\Builders;

use MahdiAbderraouf\FacturX\Enums\VatCategory;
use MahdiAbderraouf\FacturX\Tests\TestCase;

class ApplicableTradeTaxTest extends TestCase
{
    private const TAX = '//ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax';

    public function test_renders_amounts_category_and_rate_for_a_breakdown(): void
    {
        $xml = self::buildXml(self::invoiceData('basicwl'));

        $this->assertXPathValue(self::TAX . '/ram:CalculatedAmount', '100', $xml);
        $this->assertXPathValue(self::TAX . '/ram:TypeCode', 'VAT', $xml);
        $this->assertXPathValue(self::TAX . '/ram:BasisAmount', '500', $xml);
        $this->assertXPathValue(self::TAX . '/ram:CategoryCode', 'S', $xml);
        $this->assertXPathValue(self::TAX . '/ram:RateApplicablePercent', '20', $xml);
    }

    public function test_renders_one_element_per_breakdown(): void
    {
        $data = self::invoiceData('basicwl');
        $data['vatBreakdowns'][] = [
            'vatCategoryTaxAmount' => 0,
            'vatCategoryTaxableAmount' => 10,
            'vatCategory' => VatCategory::ZERO_RATED,
        ];

        $xml = self::buildXml($data);

        $this->assertXPathCount(self::TAX, 2, $xml);
        $this->assertXPathValue(self::TAX . '[2]/ram:CategoryCode', 'Z', $xml);
    }

    public function test_omits_optional_exemption_and_date_fields_by_default(): void
    {
        $xml = self::buildXml(self::invoiceData('basicwl'));

        $this->assertXPathMissing(self::TAX . '/ram:ExemptionReason', $xml);
        $this->assertXPathMissing(self::TAX . '/ram:ExemptionReasonCode', $xml);
        $this->assertXPathMissing(self::TAX . '/ram:DueDateTypeCode', $xml);
    }

    public function test_renders_exemption_reason_code_and_tax_point_date_code_when_set(): void
    {
        $data = self::invoiceData('basicwl');
        $data['vatBreakdowns'] = [[
            'vatCategoryTaxAmount' => 0,
            'vatCategoryTaxableAmount' => 500,
            'vatCategory' => VatCategory::ZERO_RATED,
            'exemptionReason' => 'Export',
            'vatExemptionReasonCode' => 'VATEX-EU-G',
            'valueAddedTaxPointDateCode' => '5',
        ]];

        $xml = self::buildXml($data);

        $this->assertXPathValue(self::TAX . '/ram:ExemptionReason', 'Export', $xml);
        $this->assertXPathValue(self::TAX . '/ram:ExemptionReasonCode', 'VATEX-EU-G', $xml);
        $this->assertXPathValue(self::TAX . '/ram:DueDateTypeCode', '5', $xml);
        $this->assertXPathMissing(self::TAX . '/ram:RateApplicablePercent', $xml);
    }

    public function test_omits_the_element_without_breakdowns(): void
    {
        $data = self::invoiceData('basicwl');
        unset($data['vatBreakdowns']);

        $xml = self::buildXml($data);

        $this->assertXPathMissing(self::TAX, $xml);
    }
}
