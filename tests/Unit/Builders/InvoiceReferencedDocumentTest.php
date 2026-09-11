<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit\Builders;

use MahdiAbderraouf\FacturX\Tests\TestCase;

class InvoiceReferencedDocumentTest extends TestCase
{
    private const REFERENCE = '//ram:ApplicableHeaderTradeSettlement/ram:InvoiceReferencedDocument';
    private const DATE = '/ram:FormattedIssueDateTime/qdt:DateTimeString';

    public function test_renders_reference_and_issue_date_in_format_102(): void
    {
        $xml = self::buildXml(self::invoiceData('basicwl'));

        $this->assertXPathValue(self::REFERENCE . '/ram:IssuerAssignedID', 'F-2024-11-15-0001', $xml);
        $this->assertXPathValue(self::REFERENCE . self::DATE, '20241115', $xml);
        $this->assertXPathValue(self::REFERENCE . self::DATE . '/@format', '102', $xml);
    }

    public function test_renders_one_element_per_preceding_invoice(): void
    {
        $data = self::invoiceData('basicwl');
        $data['precedingInvoices'][] = ['reference' => 'F-2024-10-01-0001'];

        $xml = self::buildXml($data);

        $this->assertXPathCount(self::REFERENCE, 2, $xml);
        $this->assertXPathValue(self::REFERENCE . '[2]/ram:IssuerAssignedID', 'F-2024-10-01-0001', $xml);
        $this->assertXPathMissing(self::REFERENCE . '[2]/ram:FormattedIssueDateTime', $xml);
    }

    public function test_omits_the_element_without_preceding_invoices(): void
    {
        $data = self::invoiceData('basicwl');
        unset($data['precedingInvoices']);

        $xml = self::buildXml($data);

        $this->assertXPathMissing(self::REFERENCE, $xml);
    }
}
