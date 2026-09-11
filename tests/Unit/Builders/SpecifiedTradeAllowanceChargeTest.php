<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit\Builders;

use MahdiAbderraouf\FacturX\Enums\VatCategory;
use MahdiAbderraouf\FacturX\Tests\TestCase;
use MahdiAbderraouf\FacturX\Validator;

class SpecifiedTradeAllowanceChargeTest extends TestCase
{
    private const ELEMENT = '//ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge';
    private const ALLOWANCE = self::ELEMENT . '[ram:ChargeIndicator/udt:Indicator="false"]';
    private const CHARGE = self::ELEMENT . '[ram:ChargeIndicator/udt:Indicator="true"]';

    public function test_marks_allowances_false_and_charges_true(): void
    {
        $xml = self::buildXml(self::invoiceData('basicwl'));

        $this->assertXPathValue(self::ALLOWANCE . '/ram:ActualAmount', '20', $xml);
        $this->assertXPathValue(self::CHARGE . '/ram:ActualAmount', '50', $xml);
    }

    public function test_renders_percent_reason_and_category_tax(): void
    {
        $xml = self::buildXml(self::invoiceData('basicwl'));

        $this->assertXPathValue(self::ALLOWANCE . '/ram:CalculationPercent', '5', $xml);
        $this->assertXPathValue(self::ALLOWANCE . '/ram:ReasonCode', '95', $xml);
        $this->assertXPathValue(self::ALLOWANCE . '/ram:Reason', 'Discount', $xml);
        $this->assertXPathValue(self::ALLOWANCE . '/ram:CategoryTradeTax/ram:TypeCode', 'VAT', $xml);
        $this->assertXPathValue(self::ALLOWANCE . '/ram:CategoryTradeTax/ram:CategoryCode', 'S', $xml);
        $this->assertXPathMissing(self::ALLOWANCE . '/ram:CategoryTradeTax/ram:RateApplicablePercent', $xml);
        $this->assertXPathValue(self::CHARGE . '/ram:CategoryTradeTax/ram:RateApplicablePercent', '5.5', $xml);
    }

    public function test_renders_the_basis_amount_when_set(): void
    {
        $data = self::invoiceData('basicwl');
        $data['allowances'][0]['baseAmount'] = 400.00;

        $xml = self::buildXml($data);

        $this->assertXPathValue(self::ALLOWANCE . '/ram:BasisAmount', '400', $xml);
    }

    public function test_omits_every_optional_part_for_a_bare_amount(): void
    {
        $data = self::invoiceData('basicwl');
        $data['allowances'] = [['amount' => 20.00]];

        $xml = self::buildXml($data);

        $this->assertXPathCount(self::ALLOWANCE . '/*', 2, $xml);
        $this->assertXPathValue(self::ALLOWANCE . '/ram:ActualAmount', '20', $xml);
        $this->assertXPathMissing(self::ALLOWANCE . '/ram:CategoryTradeTax', $xml);
    }

    public function test_renders_one_element_per_allowance_and_stays_xsd_valid(): void
    {
        $data = self::invoiceData('basicwl');
        $data['allowances'][] = ['amount' => 5.00, 'vatCategory' => VatCategory::STANDARD_RATE, 'vatRate' => 20.00];

        $xml = self::buildXml($data);

        $this->assertXPathCount(self::ALLOWANCE, 2, $xml);
        $this->assertXPathValue(self::ALLOWANCE . '[2]/ram:ActualAmount', '5', $xml);
        $this->assertTrue(Validator::validate($xml));
    }

    public function test_omits_the_element_without_allowances_or_charges(): void
    {
        $data = self::invoiceData('basicwl');
        unset($data['allowances'], $data['charges']);

        $xml = self::buildXml($data);

        $this->assertXPathMissing(self::ELEMENT, $xml);
    }
}
