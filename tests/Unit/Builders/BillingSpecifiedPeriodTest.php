<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit\Builders;

use MahdiAbderraouf\FacturX\Tests\TestCase;

class BillingSpecifiedPeriodTest extends TestCase
{
    private const PERIOD = '//ram:ApplicableHeaderTradeSettlement/ram:BillingSpecifiedPeriod';
    private const START = self::PERIOD . '/ram:StartDateTime/udt:DateTimeString';
    private const END = self::PERIOD . '/ram:EndDateTime/udt:DateTimeString';

    public function test_renders_both_dates_in_format_102(): void
    {
        $xml = self::buildXml(self::invoiceData('basicwl'));

        $this->assertXPathValue(self::START, '20240101', $xml);
        $this->assertXPathValue(self::START . '/@format', '102', $xml);
        $this->assertXPathValue(self::END, '20241231', $xml);
    }

    public function test_renders_only_the_start_date_when_the_end_is_unset(): void
    {
        $data = self::invoiceData('basicwl');
        unset($data['invoicingPeriodEndDate']);

        $xml = self::buildXml($data);

        $this->assertXPathValue(self::START, '20240101', $xml);
        $this->assertXPathMissing(self::END, $xml);
    }

    public function test_renders_only_the_end_date_when_the_start_is_unset(): void
    {
        $data = self::invoiceData('basicwl');
        unset($data['invoicingPeriodStartDate']);

        $xml = self::buildXml($data);

        $this->assertXPathMissing(self::START, $xml);
        $this->assertXPathValue(self::END, '20241231', $xml);
    }

    public function test_omits_the_element_without_dates(): void
    {
        $data = self::invoiceData('basicwl');
        unset($data['invoicingPeriodStartDate'], $data['invoicingPeriodEndDate']);

        $xml = self::buildXml($data);

        $this->assertXPathMissing(self::PERIOD, $xml);
    }
}
