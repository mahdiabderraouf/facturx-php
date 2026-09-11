<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit\Builders;

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Tests\TestCase;

class IncludedSupplyChainTradeLineItemTest extends TestCase
{
    private const LINE = '//rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem';
    private const FIRST = self::LINE . '[1]';
    private const SECOND = self::LINE . '[2]';
    private const GROSS = '/ram:SpecifiedLineTradeAgreement/ram:GrossPriceProductTradePrice';
    private const NET = '/ram:SpecifiedLineTradeAgreement/ram:NetPriceProductTradePrice';
    private const TAX = '/ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax';
    private const BILLED = '/ram:SpecifiedLineTradeDelivery/ram:BilledQuantity';
    private const LINE_TOTAL = '/ram:SpecifiedLineTradeSettlement'
        . '/ram:SpecifiedTradeSettlementLineMonetarySummation/ram:LineTotalAmount';

    public function test_renders_one_line_item_per_line_at_basic(): void
    {
        $xml = self::buildXml(self::invoiceData('basic'));

        $this->assertXPathCount(self::LINE, 3, $xml);
    }

    public function test_omits_line_items_below_basic(): void
    {
        $data = self::invoiceData('basic');
        $data['profile'] = Profile::BASIC_WL;

        $xml = self::buildXml($data);

        $this->assertXPathMissing(self::LINE, $xml);
    }

    public function test_renders_a_minimal_line_without_optional_parts(): void
    {
        $xml = self::buildXml(self::invoiceData('basic'));

        $this->assertXPathValue(self::FIRST . '/ram:AssociatedDocumentLineDocument/ram:LineID', '001', $xml);
        $this->assertXPathMissing(self::FIRST . '/ram:AssociatedDocumentLineDocument/ram:IncludedNote', $xml);
        $this->assertXPathValue(self::FIRST . '/ram:SpecifiedTradeProduct/ram:Name', 'Product A', $xml);
        $this->assertXPathMissing(self::FIRST . '/ram:SpecifiedTradeProduct/ram:GlobalID', $xml);
        $this->assertXPathMissing(self::FIRST . self::GROSS, $xml);
        $this->assertXPathValue(self::FIRST . self::NET . '/ram:ChargeAmount', '100', $xml);
        $this->assertXPathMissing(self::FIRST . self::NET . '/ram:BasisQuantity', $xml);
        $this->assertXPathValue(self::FIRST . self::BILLED, '3', $xml);
        $this->assertXPathValue(self::FIRST . self::BILLED . '/@unitCode', 'C62', $xml);
        $this->assertXPathValue(self::FIRST . self::TAX . '/ram:TypeCode', 'VAT', $xml);
        $this->assertXPathValue(self::FIRST . self::TAX . '/ram:CategoryCode', 'Z', $xml);
        $this->assertXPathMissing(self::FIRST . self::TAX . '/ram:RateApplicablePercent', $xml);
        $this->assertXPathValue(self::FIRST . self::LINE_TOTAL, '300', $xml);
    }

    public function test_renders_a_full_line_with_note_global_id_gross_price_and_vat_rate(): void
    {
        $xml = self::buildXml(self::invoiceData('basic'));

        $this->assertXPathValue(
            self::SECOND . '/ram:AssociatedDocumentLineDocument/ram:IncludedNote/ram:Content',
            "Dont 0,50€ d'éco-participation",
            $xml
        );
        $this->assertXPathValue(self::SECOND . '/ram:SpecifiedTradeProduct/ram:GlobalID', '67890', $xml);
        $this->assertXPathValue(self::SECOND . '/ram:SpecifiedTradeProduct/ram:GlobalID/@schemeID', '0160', $xml);
        $this->assertXPathValue(self::SECOND . self::GROSS . '/ram:ChargeAmount', '50', $xml);
        $this->assertXPathValue(self::SECOND . self::GROSS . '/ram:BasisQuantity', '1', $xml);
        $this->assertXPathValue(self::SECOND . self::GROSS . '/ram:BasisQuantity/@unitCode', 'XBX', $xml);
        $this->assertXPathValue(self::SECOND . self::NET . '/ram:BasisQuantity/@unitCode', 'XBX', $xml);
        $this->assertXPathValue(self::SECOND . self::TAX . '/ram:CategoryCode', 'S', $xml);
        $this->assertXPathValue(self::SECOND . self::TAX . '/ram:RateApplicablePercent', '20', $xml);
    }

    public function test_renders_a_price_discount_as_an_allowance_on_the_gross_price(): void
    {
        $data = self::invoiceData('basic');
        $data['lines'][1]['priceDiscount'] = 5.00;

        $xml = self::buildXml($data);

        $discount = self::SECOND . self::GROSS . '/ram:AppliedTradeAllowanceCharge';
        $this->assertXPathValue($discount . '/ram:ChargeIndicator/udt:Indicator', 'false', $xml);
        $this->assertXPathValue($discount . '/ram:ActualAmount', '5', $xml);
        $this->assertXPathMissing(self::LINE . '[3]' . self::GROSS . '/ram:AppliedTradeAllowanceCharge', $xml);
    }

    public function test_omits_the_unit_code_when_the_unit_is_empty(): void
    {
        $data = self::invoiceData('basic');
        $data['lines'][0]['invoicedQuantityUnit'] = '';

        $xml = self::buildXml($data);

        $this->assertXPathValue(self::FIRST . self::BILLED, '3', $xml);
        $this->assertXPathMissing(self::FIRST . self::BILLED . '/@unitCode', $xml);
    }

    public function test_renders_a_line_billing_period_and_line_allowances(): void
    {
        $data = self::invoiceData('basic');
        $data['lines'][0]['startDate'] = new \DateTime('2024-11-01');
        $data['lines'][0]['endDate'] = new \DateTime('2024-11-30');
        $data['lines'][0]['allowances'] = [['amount' => 2.50, 'reason' => 'Line discount']];

        $xml = self::buildXml($data);

        $period = self::FIRST . '/ram:SpecifiedLineTradeSettlement/ram:BillingSpecifiedPeriod';
        $allowance = self::FIRST . '/ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge';
        $this->assertXPathValue($period . '/ram:StartDateTime/udt:DateTimeString', '20241101', $xml);
        $this->assertXPathValue($period . '/ram:EndDateTime/udt:DateTimeString', '20241130', $xml);
        $this->assertXPathValue($allowance . '/ram:ActualAmount', '2.5', $xml);
        $this->assertXPathValue($allowance . '/ram:Reason', 'Line discount', $xml);
    }

    public function test_defaults_the_standard_identifier_scheme_to_gtin(): void
    {
        $data = self::invoiceData('basic');
        $data['lines'][0]['standardIdentifier'] = '4006381333931';

        $xml = self::buildXml($data);

        $this->assertXPathValue(self::FIRST . '/ram:SpecifiedTradeProduct/ram:GlobalID', '4006381333931', $xml);
        $this->assertXPathValue(self::FIRST . '/ram:SpecifiedTradeProduct/ram:GlobalID/@schemeID', '0160', $xml);
    }
}
